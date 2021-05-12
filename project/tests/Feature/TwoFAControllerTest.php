<?php

namespace Tests\Feature;

use App\Mail\UserNewLoginEmail;
use App\Services\InternalApiClient;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use Tests\ApiTestCase;

class TwoFAControllerTest extends ApiTestCase
{
    public function testGoogle2FASetup()
    {
        //Create user and get token
        $user = $this->createUser();
        $token = $this->getUserToken($user);

        //Assert that obtained token is not verified yet
        $user = $user->fresh()->load('tokens');
        $this->assertEquals($user->tokens()->first()->two_fa_verified, 0);

        //Request to setup a new 2FA with Google Authenticator
        $headers = $this->getHeaders($token);
        $response = $this->apiGet('twofa/setup/ga', $headers);
        $response->assertStatus(200);

        //Assert that 2fa setup returns imageurl and secret
        $response->assertJsonStructure(['secret', 'imageurl']);
        $secret = $response->json()['secret'];

        //Test setup complete fails with an incorrect one time password
        $otp = '111111';
        $response = $this->apiGet('twofa/setupcomplete/'.$otp, $headers);
        $response->assertStatus(400);

        //Create a valid one time password using the secret (Simulate the OTP generation on mobile authenticator
        $otp = (new Google2FA())->getCurrentOtp($secret);

        //Make sure valit OTP passes validation.
        $response = $this->apiGet('twofa/setupcomplete/'.$otp, $headers);
        $response->assertStatus(200);

        //Reload the the user tokens and assert that it is now verified.
        $user = $user->fresh()->load('tokens');
        $this->assertEquals($user->tokens()->first()->two_fa_verified, 1);
        $this->assertEquals(decrypt($user->two_fa_secret), $secret);
        $this->assertEquals($user->two_fa_method, 'ga');
        $this->assertEquals($user->two_fa_enabled, 1);
        $this->resetState();

        //Test verify method with a new token
        $token = $this->getUserToken($user);

        //Assert that it does not work without verification
        $headers = $this->getHeaders($token);
        $response = $this->apiGet('user', $headers);
        $response->assertStatus(412);

        //Verify the token
        Mail::fake();
        $otp = (new Google2FA())->getCurrentOtp($secret);
        $response = $this->apiPost('twofa/verify', ['otp' => $otp], $headers);
        $response->assertStatus(200);
        Mail::assertQueued(UserNewLoginEmail::class);

        $headers = $this->getHeaders($token);
        $response = $this->apiGet('user', $headers);
        $response->assertStatus(200);

        //Cancel 2FA
        $otp = (new Google2FA())->getCurrentOtp($secret);
        $response = $this->apiPost('twofa/cancel', ['otp' => $otp], $headers);
        $response->assertStatus(200);

        $user = $user->fresh();
        $this->assertEquals($user->two_fa_enabled, 0);
        $this->assertNull($user->two_fa_method);
        $this->assertNull($user->two_fa_secret);
        $this->assertNull($user->two_fa_otp);
    }

    public function testSms2FASetup()
    {
        $phone = config('sms.testnumber');
        //Create user and get token
        $user = $this->createUser();
        $token = $this->getUserToken($user);

        //Assert that obtained token is not verified yet
        $user = $user->fresh()->load('tokens');
        $this->assertEquals($user->tokens()->first()->two_fa_verified, 0);

        //Request to setup a new 2FA with SMS for given number
        $headers = $this->getHeaders($token);
        $response = $this->apiPost('twofa/setup/sms', ['phone' => $phone], $headers);
        $response->assertStatus(200);

        $user = $user->fresh();
        $this->assertEquals($user->two_fa_secret, $phone);
        $this->assertEquals($user->two_fa_method, 'sms');

        //Test setup complete fails with an incorrect one time password
        $otp = '______';
        $response = $this->apiGet('twofa/setupcomplete/'.$otp, $headers);
        $response->assertStatus(400);

        $otp = $user->two_fa_otp;

        //Make sure valid OTP passes validation.
        $response = $this->apiGet('twofa/setupcomplete/'.$otp, $headers);
        $response->assertStatus(200);

        //Reload the the user tokens and assert that it is now verified.
        $user = $user->fresh()->load('tokens');
        $this->assertEquals($user->tokens()->first()->two_fa_verified, 1);
        $this->assertEquals($user->two_fa_method, 'sms');
        $this->assertEquals($user->two_fa_enabled, 1);
        $this->resetState();

        //Test verify method with a new token
        $token = $this->getUserToken($user);

        //Assert that it does not work without verification
        $headers = $this->getHeaders($token);
        $response = $this->apiGet('user', $headers);
        $response->assertStatus(412);

        $response = $this->apiGet('twofa/request', $headers);
        $response->assertStatus(200);

        //Get the generated OTP from users table
        $user = $user->fresh();
        $otp = $user->two_fa_otp;

        //Verify new token
        Mail::fake();
        $response = $this->apiPost('twofa/verify', ['otp' => $otp], $headers);
        $response->assertStatus(200);
        Mail::assertQueued(UserNewLoginEmail::class);

        //Use new token
        $headers = $this->getHeaders($token);
        $response = $this->apiGet('user', $headers);
        $response->assertStatus(200);

        //### TEST CANCELLING ###
        $this->resetState();

        $response = $this->apiGet('twofa/request', $headers);
        $response->assertStatus(200);

        //Get the generated OTP from users table
        $user = $user->fresh();
        $otp = $user->two_fa_otp;

        //Cancel 2FA
        $response = $this->apiPost('twofa/cancel', ['otp' => $otp], $headers);
        $response->assertStatus(200);

        //Cancel 2fa with sms
        $user = $user->fresh();
        $this->assertEquals($user->two_fa_enabled, 0);
        $this->assertNull($user->two_fa_method);
        $this->assertNull($user->two_fa_secret);
        $this->assertNull($user->two_fa_otp);
    }

    public function testRefreshTokenReturnsVerifiedAccessToken()
    {
        //Create a 2fa enabled user
        $user = $this->createUser([
            'two_fa_method' => 'sms',
            'two_fa_otp' => '123123',
            'two_fa_enabled' => true,
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'Secret1!',
        ];

        Mail::fake();
        $response = $this->apiPost('user/login', $data);
        $response->assertStatus(200);
        $json = $response->json();

        $accessToken = $json['access_token'];
        $refreshToken = $json['refresh_token'];

        $data = [
            'grant_type' => 'refresh_token',
            'client_id' => InternalApiClient::getClientId(),
            'client_secret' => InternalApiClient::getClientSecret(),
            'refresh_token' => $refreshToken,
            'scope' => '',
        ];

        //refresh token when not verified should fail
        $response = $this->postJson('/oauth/token', $data);
        $response->assertStatus(401);

        //Verify new token
        Mail::fake();
        $headers = $this->getHeaders($accessToken);
        $response = $this->apiPost('twofa/verify', ['otp' => '123123'], $headers);
        $response->assertStatus(200);
        Mail::assertQueued(UserNewLoginEmail::class);

        //refresh token when verified should success
        $response = $this->postJson('/oauth/token', $data);
        $response->assertStatus(200);

        $json = $response->json();
        $accessToken = $json['access_token'];

        //force logout
        $this->resetState();

        //New access token should be able to used directly without 2fa verification
        $headers = $this->getHeaders($accessToken);
        $response = $this->apiGet('user', $headers);
        $response->assertStatus(200);
    }

    public function testSmsTimeOut()
    {
        $phone = config('sms.testnumber');

        $user = $this->createUser([
            'two_fa_method' => 'sms',
            'two_fa_otp' => null,
            'two_fa_enabled' => true,
            'two_fa_secret' => $phone,

        ]);
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        //First sms request should succeed
        $response = $this->apiGet('twofa/request', $headers);
        $response->assertStatus(200);

        //Request new sms code, it should return 400 because of timeout.
        //We already requested an sms for this user during setup, so we must wait until timeout passes
        $response = $this->apiGet('twofa/request', $headers);
        $response->assertStatus(400);
        $response->json(['message' => 'sms_timeout_error']);
        sleep(config('sms.timeout'));

        //Request new sms code
        $response = $this->apiGet('twofa/request', $headers);
        $response->assertStatus(200);

        //Get the generated OTP from users table
        $user = $user->fresh();
        $otp = $user->two_fa_otp;

        //Verify new token
        Mail::fake();
        $response = $this->apiPost('twofa/verify', ['otp' => $otp], $headers);
        $response->assertStatus(200);
        Mail::assertQueued(UserNewLoginEmail::class);

        //Request new sms code after successfull 2fa verify, there should not be a timeout
        $response = $this->apiGet('twofa/request', $headers);
        $response->assertStatus(200);
    }
}

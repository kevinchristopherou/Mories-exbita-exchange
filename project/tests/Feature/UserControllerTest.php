<?php

namespace Tests\Feature;

use App\Jobs\Wallets\CreateUserWallets;
use App\Mail\UserForgotPasswordEmail;
use App\Mail\UserResetPasswordEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\ApiTestCase;

class UserControllerTest extends ApiTestCase
{
    public function testUserRegister()
    {
        $data = [
            'name' => 'John Coin',
            'email' => 'john@coin.com',
            'password' => 'Secret1!',
            'password_confirmation' => 'Secret1!',
            'g_recaptcha_response' => '1',
        ];

        $response = $this->apiPost('user/register', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $data['email'], 'status' => 0]);
    }

    public function testUserCanVerifyAndDeactivate()
    {
        Bus::fake();

        //To be able to verify, lets create an inactive user.
        $user = $this->createUser(['status' => 0]);

        //Check user created with status 0
        $this->assertDatabaseHas('users', ['email' => $user['email'], 'status' => 0]);

        $response = $this->apiGet('user/'.$user->id.'/verify/'.$user->verify_token);
        $response->assertStatus(200);

        //Check user status updated as 1
        $this->assertDatabaseHas('users', ['email' => $user['email'], 'status' => 1]);

        $updatedUser = User::where('email', $user['email'])->first();

        //Check token is changed after verification
        $this->assertNotEquals($user->verify_token, $updatedUser->verify_token);

        Bus::assertDispatched(CreateUserWallets::class, function ($job) use ($user) {
            return $job->user->id === $user->id;
        });

        //Test user can deactivate
        $user = $user->fresh();
        $response = $this->apiGet('user/'.$user->id.'/deactivate/'.$user->verify_token);
        $response->assertStatus(200);

        $user = $user->fresh();
        $this->assertEquals(2, $user->status);
    }

    public function testUserForgotPassword()
    {
        Mail::fake();
        //Create an active user
        $user = $this->createUser(['status' => 1]);

        $data = [
            'email' => $user->email,
            'g_recaptcha_response' => '1',
        ];
        $response = $this->apiPost('user/forgotpassword', $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email,
        ]);

        Mail::assertQueued(UserForgotPasswordEmail::class);
    }

    public function testUserResetPassword()
    {
        Mail::fake();
        //Create an active user
        $user = $this->createUser(['status' => 1]);

        $data = [
            'email' => $user->email,
            'password' => 'Secret1!',
        ];

        //Login to obtain a token with old password
        Mail::fake();
        $response = $this->apiPost('user/login', $data);
        $response->assertStatus(200);
        $oldAccessToken = $response->json()['access_token'];

        //Create a new password reset token via Laravel's PasswordBroker
        $token = Password::createToken($user);

        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'token' => $token,
            'password' => 'Secret2!',
            'password_confirmation' => 'Secret2!',
        ];

        $response = $this->apiPost('user/resetpassword', $data);
        $response->assertStatus(200);
        Mail::assertQueued(UserResetPasswordEmail::class);

        $data = [
            'email' => $user->email,
            'password' => 'Secret2!',
        ];

        //Account should be locked
        $response = $this->apiPost('user/login', $data);
        $response->assertStatus(401);
        $response->assertJson(['message' => 'account_locked_24h']);

        //Update password reset time
        $user->fresh();
        $user->last_password_reset = Carbon::now()->subHour(25);
        $user->save();

        $data['g_recaptcha_response'] = '1';

        Mail::fake();
        $response = $this->apiPost('user/login', $data);
        $response->assertStatus(200);

        $this->assertEquals($user->tokens()->get()->search(function ($item, $key) use ($oldAccessToken) {
            return $item->id == $oldAccessToken;
        }), false);

        //todo: check if refresh tokens are also invalidated.
    }

    public function testSetLocale()
    {
        $user = $this->createUser();
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $response = $this->apiPut('user/setlocale', ['locale' => 'tr'], $headers);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'locale' => 'tr']);

        $response = $this->apiPut('user/setlocale', ['locale' => 'en'], $headers);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'locale' => 'en']);
    }
}

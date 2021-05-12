<?php

namespace Tests\Feature;

use App\Jobs\FF;
use App\Mail\UserPasswordChangedEmail;
use Illuminate\Support\Facades\Mail;
use Tests\ApiTestCase;

class UserControllerLoginLogoutTest extends ApiTestCase
{
    public function testUserCanLoginLogout()
    {
        $user = $this->createUser();

        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $response = $this->apiGet('user', $headers);
        $response->assertStatus(200);

        $response = $this->apiPost('user/logout', [], $headers);
        $response->assertStatus(200);

        //This forces application to reset so autheticated user in the app instance gets destroyed
        $this->resetState();

        //Try to fetch data with logged out token
        $response = $this->apiGet('user', $headers);
        $response->assertStatus(401);
    }

    public function testUserChangePassword()
    {
        Mail::fake();
        //Create an active user
        $user = $this->createUser();
        $token = $this->getUserToken($user);
        $token2 = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $data = [
            'current_password' => 'Secret1!',
            'password' => 'Secret2!',
            'password_confirmation' => 'Secret2!',
        ];

        $response = $this->apiPut('user/changepassword', $data, $headers);
        $response->assertStatus(200);

        Mail::assertQueued(UserPasswordChangedEmail::class);

        $this->resetState();

        //Assert current token still works
        $headers = $this->getHeaders($token);
        $response = $this->apiGet('user', $headers);
        $response->assertStatus(200);

        $this->resetState();

        //Assert can get new token with new password
        $token = $this->getUserToken($user, 'Secret2!');
        $headers = $this->getHeaders($token);
        $response = $this->apiGet('user', $headers);
        $response->assertStatus(200);

        $this->resetState();

        //Assert a token retrieved before password changed no longer works.
        $headers = $this->getHeaders($token2);
        $response = $this->apiGet('user', $headers);
        $response->assertStatus(401);
    }
}

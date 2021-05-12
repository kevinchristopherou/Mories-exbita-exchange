<?php

namespace Tests\Feature;

use App\Http\Middleware\LoginThrottler;
use Tests\ApiTestCase;

class LoginThrottlerMiddlewareTest extends ApiTestCase
{
    public function testLoginThrottleLimits()
    {
        $user = $this->createUser();

        $data = [
            'email' => $user->email,
            'password' => 'WrongPassword',
            'g_recaptcha_response' => '1',
        ];

        //Retry until max requests reached
        for ($i = 0; $i <= LoginThrottler::MAX_REQUESTS; $i++) {
            $response = $this->apiPost('user/login', $data);
            $response->assertStatus(401);
        }

        $response = $this->apiPost('user/login', $data);
        $response->assertStatus(429);
    }
}

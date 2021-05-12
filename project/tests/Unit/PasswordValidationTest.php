<?php

namespace Tests\Unit;

use App\Services\SmsClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PasswordValidationTest extends TestCase
{
    public function testPasswordValidation()
    {
        $validator = Validator::make(['password' => 'Secret11'], [
            'password' => 'required|string|min:8|strong_password',
        ]);

        $this->assertTrue($validator->passes());
    }
}

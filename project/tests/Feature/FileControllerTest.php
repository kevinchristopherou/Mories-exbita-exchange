<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\ApiTestCase;

class FileControllerTest extends ApiTestCase
{
    public function testUploadFile()
    {
        $user = $this->createUser();
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $response = $this->apiPost('file/upload', [
            'file' => UploadedFile::fake()->image('upload_test_file.jpg', 600, 600),
        ], $headers);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'tmpName', 'expiresIn',
        ]);
    }
}

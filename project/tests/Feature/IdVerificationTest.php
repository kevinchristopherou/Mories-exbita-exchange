<?php

namespace Tests\Feature;

use App\UserIdDocument;
use Illuminate\Http\UploadedFile;
use Tests\ApiTestCase;

class IdVerificationTest extends ApiTestCase
{
    private function uploadPhoto($filename, $headers)
    {
        $response = $this->apiPost('file/upload', [
            'file' => UploadedFile::fake()->image($filename, 600, 600),
        ], $headers);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'tmpName', 'expiresIn',
        ]);

        return $response;
    }

    public function testUserCanUploadDocuments()
    {
        $user = $this->createUser();
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $response = $this->apiPost('iddocuments', [
            'ssid' => '11111111110',
        ], $headers);

        $response->assertStatus(422);
        //todo: add php file upload test
        $response1 = $this->uploadPhoto('id_photo.jpg', $headers);
        $response2 = $this->uploadPhoto('selfie_photo.jpg', $headers);
        $response3 = $this->uploadPhoto('address_photo.jpg', $headers);

        $identityPhoto = $response1->json()['tmpName'];
        $selfiePhoto = $response2->json()['tmpName'];
        $addressPhoto = $response3->json()['tmpName'];

        $response = $this->apiPost('iddocuments', [
            'identity_photo' => $identityPhoto,
            'selfie_photo' => $selfiePhoto,
            'address_photo' => $addressPhoto,
            'name' => $user->name,
            'ssid' => '11111111110',
            'address' => 'Acme Street.',
        ], $headers);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_id_documents', ['user_id' => $user->id, 'status' => 'waiting']);
        $idDocuments = UserIdDocument::where('user_id', $user->id)->first();

        $this->assertDatabaseHas('attachments', ['id' => $idDocuments->identity_photo_id]);
        $this->assertDatabaseHas('attachments', ['id' => $idDocuments->selfie_photo_id]);
        $this->assertDatabaseHas('attachments', ['id' => $idDocuments->address_photo_id]);
    }
}

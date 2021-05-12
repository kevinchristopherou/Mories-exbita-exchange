<?php

namespace Tests\Feature;

use App\Models\FiatWallet;
use App\User;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\ClientRepository;
use Tests\ApiTestCase;
use Tests\TestCase;

class PersonalTokenTest extends ApiTestCase
{
    // Create personal access token
    public function testCreateToken()
    {
        $user = User::first();
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);
        $response = $this->apiPost('user/personaltoken?name=Token', [], $headers);

        $response->assertStatus(200);
        $response->assertJsonStructure(['accessToken']);

        $content = json_decode($response->getContent());

        $this->assertDatabaseHas('oauth_access_tokens', ['user_id' => $user->id, 'id' => $content->token->id]);
    }

    // Create personal access token
    public function testRevokeToken()
    {
        $user = User::first();
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);
        $response = $this->apiPost('user/personaltoken?name=Token', [], $headers);

        $response->assertStatus(200);
        $response->assertJsonStructure(['accessToken']);

        $content = json_decode($response->getContent());

        $token_id = $content->token->id;

        // Check if token is created
        $this->assertDatabaseHas('oauth_access_tokens', ['user_id' => $user->id, 'id' => $token_id]);

        $response = $this->apiDelete('user/personaltoken/'.$token_id, [], $headers);

        $response->assertStatus(204);

        // Check if token is revoked
        $this->assertDatabaseHas('oauth_access_tokens', ['user_id' => $user->id, 'id' => $token_id, 'revoked' => 1]);
    }
}

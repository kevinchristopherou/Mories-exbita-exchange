<?php

namespace Tests\Feature;

use Tests\ApiTestCase;

class CurrencyControllerTest extends apiTestCase
{
    public function testGetCurrenciesForUser()
    {
        // Get response
        $response = $this->apiGet('currencies');

        // Check response status
        $response->assertStatus(200);

        // Parse received json response
        $data = json_decode($response->getContent(), true);

        // Check if exist data key
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayNotHasKey('withdraw_pending', $data['data'][0]);
        $this->assertArrayNotHasKey('withdraw_pending_count', $data['data'][0]);
        $this->assertArrayNotHasKey('account_balance', $data['data'][0]);
    }

    public function testGetCurrenciesForAdmin()
    {
        $headers = $this->getAdminHeaders();
        // Approve withdraw
        $response = $this->apiGet('currencies', $headers);
        $response->assertStatus(200);

        // Parse received json response
        $data = json_decode($response->getContent(), true);

        // Check if exist data key
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('withdraw_pending', $data['data'][0]);
        $this->assertArrayHasKey('withdraw_pending_count', $data['data'][0]);
        $this->assertArrayHasKey('account_balance', $data['data'][0]);
    }
}

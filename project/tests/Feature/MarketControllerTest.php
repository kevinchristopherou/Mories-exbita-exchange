<?php

namespace Tests\Feature;

use App\Models\Market;
use Tests\ApiTestCase;
use Tests\TestCase;

class MarketControllerTest extends ApiTestCase
{
    /**
     * Get a list of trading markets.
     *
     * @return void
     */
    public function testGetMarkets()
    {
        // Get response
        $response = $this->apiGet('markets');

        // Check response status
        $response->assertStatus(200);

        // Parse received json response
        $data = json_decode($response->getContent(), true);

        // Check if exist data key
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('success', $data);
    }

    /**
     * Get a list of markets summaries.
     *
     * @return void
     */
    public function testGetMarket()
    {
        // Get response
        $response = $this->apiGet('market/BTC-USD');

        // Check response status
        $response->assertStatus(200);

        // Parse received json response
        $data = json_decode($response->getContent(), true);

        // Check if exist data key
        $this->assertArrayHasKey('data', $data);
    }

    /**
     * Get the market orderbook.
     *
     * @return void
     */
    public function testGetOrderbook()
    {
        $market = Market::first();

        // Get response
        $response = $this->apiGet("market/{$market->name}/orderbook/both");

        // Check response status
        $response->assertStatus(200);

        // Assert the structure of response
        $response->assertJsonStructure([
            'success',
            'data',
        ]);
    }

    /**
     * Get the market history.
     *
     * @return void
     */
    public function testGetHistory()
    {
        $market = Market::first();

        // Get response
        $response = $this->apiGet("market/{$market->name}/history");

        // Check response status
        $response->assertStatus(200);

        // Assert the structure of response
        $response->assertJsonStructure([
            'success',
            'data',
        ]);
    }
}

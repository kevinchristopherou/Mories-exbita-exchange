<?php

namespace Tests\Feature;

use App\Services\Api\Btc;
use Tests\ApiTestCase;

class BitcoinTest extends ApiTestCase
{
    protected function isSkip()
    {
        if (env('SKIP_WALLET_TESTS', 0) == 1) {
            $this->assertTrue(true);

            return true;
        }

        return false;
    }

    public function testCreateAddress()
    {
        if ($this->isSkip()) {
            return;
        }

        $address = bitcoind()->getnewaddress()->get();
        $this->assertEquals(35, strlen($address));
        $firstLetter = substr($address, 0, 1);
        //Testnet addresses starts with 2
        $this->assertEquals(2, $firstLetter);
    }

    public function testValidateAddress()
    {
        if ($this->isSkip()) {
            return;
        }

        $response = bitcoind()->validateaddress('2N8hwP1WmJrFF5QWABn38y63uYLhnJYJYTF')->get();
        $this->assertTrue($response['isvalid']);
    }

    public function testGetbalance()
    {
        if ($this->isSkip()) {
            return;
        }

        $response = bitcoind()->getbalance()->get();
        $this->assertTrue(is_numeric($response));
    }

    public function testGetSendTxFee()
    {
        if ($this->isSkip()) {
            return;
        }

        //note: create a test tx and use its id to avoid send test btc at each test
        $fee = Btc::getSendTxFee('bc6843e221ba6db24ebad86bddd1e61068fdb9ae993b641f3d2dce1af85a0c1e');
        $this->assertTrue(is_numeric($fee));
    }

//    public function testSendBitcoins()
//    {
//        $response = bitcoind()->sendToAddress("mwx9rNmUn3YG8RFaCdT8sjbqv6AEaX5vb8",0.01);
//        dump($response);
//    }
//
//    public function testGetAddresses()
//    {
//        $response = bitcoind()->getaddressesbyaccount('')->get();
//        dump($response);
//
//
//        $response = bitcoind()->listaddressgroupings()->get();
//        dump($response);
//    }
//
//    public function testGetFee()
//    {
//
//        for ($i = 2; $i <= 25; $i++) {
//            $response = bitcoind()->estimatefee($i)->get();
//            echo sprintf('%.8f' . PHP_EOL, floatval($response));
//        }
//
//    }
//
//    public function testTransactions()
//    {
//
//        $response = bitcoind()->listtransactions()->get();
//    }
//
//    public function testSendBitcoins()
//    {
//        $response = bitcoind()->getbalance('')->get();
//        dump($response);
//        $response = bitcoind()->sendToAddress("2N8hwP1WmJrFF5QWABn38y63uYLhnJYJYTF", 0.02)->get();
//        dump($response);
//        $fee = Btc::getSendTxFee($response);
//        dump($fee);
//        $response = bitcoind()->getbalance('')->get();
//        dump($response);
//    }
//
}

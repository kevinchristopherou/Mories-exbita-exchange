<?php

namespace Tests\Unit;

use App\Helpers\MoneyHelper;
use Tests\TestCase;

class MoneyHelperTest extends TestCase
{
    public function testParseCrypto()
    {
        $decimals = 8;

        $money = MoneyHelper::parseCrypto('0.03859496', $decimals);
        $this->assertEquals(3859496, $money->getAmount());
        $money = MoneyHelper::parseCrypto(0.03859496, $decimals);
        $this->assertEquals(3859496, $money->getAmount());

        $money = MoneyHelper::parseCrypto(0.0385, $decimals);
        $this->assertEquals(3850000, $money->getAmount());
        $money = MoneyHelper::parseCrypto('0.0385');
        $this->assertEquals(3850000, $money->getAmount());

        $money = MoneyHelper::parseCrypto(8.9999, $decimals);
        $this->assertEquals(899990000, $money->getAmount());
        $money = MoneyHelper::parseCrypto('8.9999');
        $this->assertEquals(899990000, $money->getAmount());

        $money = MoneyHelper::parseCrypto(0.1, $decimals);
        $this->assertEquals(10000000, $money->getAmount());

        $money = MoneyHelper::parseCrypto('0.20000000000000', $decimals);
        $this->assertEquals(20000000, $money->getAmount());
    }
}

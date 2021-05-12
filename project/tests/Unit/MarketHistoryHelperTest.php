<?php

namespace Tests\Unit;

use App\Helpers\MarketHistoryHelper;
use Carbon\Carbon;
use Tests\TestCase;

class MarketHistoryHelperTest extends TestCase
{
    public function testGetIntervalStart()
    {
        $helper = new MarketHistoryHelper();
        $pivot = Carbon::create(2018, 5, 5, 15, 45, 50);

        //Test 1 hour interval
        $start = $helper->getIntervalStart('60', $pivot);

        $this->assertEquals(2018, $start->year);
        $this->assertEquals(5, $start->month);
        $this->assertEquals(5, $start->day);
        $this->assertEquals(15, $start->hour);
        $this->assertEquals(0, $start->minute);
        $this->assertEquals(0, $start->second);

        //Test 4 hours interval
        $start = $helper->getIntervalStart('240', $pivot);

        $this->assertEquals(2018, $start->year);
        $this->assertEquals(5, $start->month);
        $this->assertEquals(5, $start->day);
        $this->assertEquals(12, $start->hour);
        $this->assertEquals(0, $start->minute);
        $this->assertEquals(0, $start->second);

        //Test 12 hours interval
        $pivot->hour = 11;
        $start = $helper->getIntervalStart('720', $pivot);

        $this->assertEquals(2018, $start->year);
        $this->assertEquals(5, $start->month);
        $this->assertEquals(5, $start->day);
        $this->assertEquals(0, $start->hour);
        $this->assertEquals(0, $start->minute);
        $this->assertEquals(0, $start->second);

        //Test 1 Day interval
        $pivot->hour = 23;
        $start = $helper->getIntervalStart('1D', $pivot);

        $this->assertEquals(2018, $start->year);
        $this->assertEquals(5, $start->month);
        $this->assertEquals(5, $start->day);
        $this->assertEquals(0, $start->hour);
        $this->assertEquals(0, $start->minute);
        $this->assertEquals(0, $start->second);

        //Test 1 week interval
        $pivot->day = 10;
        $start = $helper->getIntervalStart('1W', $pivot);

        $this->assertEquals(2018, $start->year);
        $this->assertEquals(5, $start->month);
        $this->assertEquals(7, $start->day);
        $this->assertEquals(0, $start->hour);
        $this->assertEquals(0, $start->minute);
        $this->assertEquals(0, $start->second);

        //Test 1 week interval previous month
        $pivot->day = 5;
        $start = $helper->getIntervalStart('1W', $pivot);

        $this->assertEquals(2018, $start->year);
        $this->assertEquals(4, $start->month);
        $this->assertEquals(30, $start->day);
        $this->assertEquals(0, $start->hour);
        $this->assertEquals(0, $start->minute);
        $this->assertEquals(0, $start->second);

        //Test 1 month interval
        $start = $helper->getIntervalStart('1M', $pivot);

        $this->assertEquals(2018, $start->year);
        $this->assertEquals(5, $start->month);
        $this->assertEquals(1, $start->day);
        $this->assertEquals(0, $start->hour);
        $this->assertEquals(0, $start->minute);
        $this->assertEquals(0, $start->second);
    }

    public function testGetIntervalEnd()
    {
        $helper = new MarketHistoryHelper();
        $start = Carbon::create(2018, 5, 5, 10);

        $end = $helper->getIntervalEnd('60', $start);
        $this->assertEquals(11, $end->hour);

        $start = Carbon::create(2018, 5, 5, 12);

        $end = $helper->getIntervalEnd('240', $start);
        $this->assertEquals(16, $end->hour);

        //Test interval 12 hours
        $start = Carbon::create(2018, 5, 5, 12);

        $end = $helper->getIntervalEnd('720', $start);

        $this->assertEquals(0, $end->hour);
        $this->assertEquals($start->day + 1, $end->day);

        //Test interval 1 day
        $start = Carbon::create(2018, 5, 5, 0, 0, 0);

        $end = $helper->getIntervalEnd('1D', $start);

        $this->assertEquals(0, $end->hour);
        $this->assertEquals($start->day + 1, $end->day);

        //Test interval 1 week
        $start = Carbon::create(2018, 4, 30, 0, 0, 0);

        $end = $helper->getIntervalEnd('1W', $start);

        $this->assertEquals(0, $end->hour);
        $this->assertEquals(7, $end->day);

        //Test interval 1 month
        $start = Carbon::create(2018, 5, 1, 0, 0, 0);

        $end = $helper->getIntervalEnd('1M', $start);

        $this->assertEquals(0, $end->hour);
        $this->assertEquals(1, $end->day);
        $this->assertEquals(6, $end->month);
    }

    public function testIsIntervalLive()
    {
        Carbon::setTestNow(Carbon::create(2018, 5, 5, 10, 13, 20));
        $helper = new MarketHistoryHelper();

        //Test 1 hour
        $start = Carbon::create(2018, 5, 5, 10);
        $this->assertTrue($helper->isIntervalLive('60', $start));

        $start = Carbon::create(2018, 5, 5, 9);
        $this->assertFalse($helper->isIntervalLive('60', $start));

        //Test 4 hours
        $start = Carbon::create(2018, 5, 5, 10);
        $this->assertTrue($helper->isIntervalLive('240', $start));

        $start = Carbon::create(2018, 5, 5, 8);
        $this->assertTrue($helper->isIntervalLive('240', $start));

        $start = Carbon::create(2018, 5, 5, 7);
        $this->assertFalse($helper->isIntervalLive('240', $start));

        //Test 12 hours
        $start = Carbon::create(2018, 5, 5, 10);
        $this->assertTrue($helper->isIntervalLive('720', $start));

        $start = Carbon::create(2018, 5, 5, 0);
        $this->assertTrue($helper->isIntervalLive('720', $start));

        $start = Carbon::create(2018, 5, 4, 23);
        $this->assertFalse($helper->isIntervalLive('720', $start));
    }
}

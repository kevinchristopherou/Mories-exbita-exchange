<?php

namespace App\Services;

use App\Events\MarketPriceUpdated;
use App\Models\Market;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarketManager
{
    public static function priceUpdated($rate, $market)
    {
        $market->last = $rate;
        $market->update();

        event(new MarketPriceUpdated($market));
    }

    public static function updateBidAsk(Market $market)
    {
        $id = $market->id;

        $sql = "UPDATE markets SET
        bid = IFNULL((SELECT max(rate) as bid FROM orders 
                WHERE market_id = $id AND type = 'BUY_LIMIT' 
                AND deleted_at IS NULL AND fill_status != 2), 0),
        ask = IFNULL((SELECT min(rate) as ask FROM orders 
                WHERE market_id = $id AND type = 'SELL_LIMIT' 
                AND deleted_at IS NULL AND fill_status != 2), 0)
        WHERE id = $id";

        $rows = DB::update($sql);

        //Broadcast if bid or ask is updated.
        $updatedMarket = Market::find($market->id);
        event(new MarketPriceUpdated($updatedMarket));
    }

    public static function get24hHigh(Market $market)
    {
        $result = DB::table('transactions')
            ->where('market_id', $market->id)
            ->where('type', 'buy')
            ->where('created_at', '>=', Carbon::now()->subDay(1))
            ->groupBy('market_id')
            ->max('rate');

        return $result ? $result : 0;
    }

    public static function get24hLow(Market $market)
    {
        $result = DB::table('transactions')
            ->where('market_id', $market->id)
            ->where('type', 'buy')
            ->where('created_at', '>=', Carbon::now()->subDay(1))
            ->groupBy('market_id')
            ->min('rate');

        return $result ? $result : 0;
    }

    public static function get24hVolume(Market $market)
    {
        $volume = DB::table('transactions')
            ->where('market_id', $market->id)
            ->where('type', 'buy')
            ->groupBy('market_id')
            ->where('created_at', '>=', Carbon::now()->subDay(1))
            ->sum('crypto_amount');

        return $volume;
    }

    public static function get24hChange(Market $market)
    {
        $oldPrice = self::get24PriceChange($market);

        $newPrice = self::getLastMarketPrice($market);

        if ($oldPrice === null || $newPrice === null) {
            return 0;
        }

        return $newPrice->rate->subtract($oldPrice->rate);
    }

    public static function get24PriceChange(Market $market) {
        return Transaction::where('market_id', $market->id)
            ->where('created_at', '<=', Carbon::now()->subHours(24))
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public static function getLastMarketPrice(Market $market) {
        return Transaction::where('market_id', $market->id)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();
    }
}

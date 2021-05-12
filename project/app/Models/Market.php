<?php

namespace App\Models;

use App\Services\MarketManager;
use App\Traits\HasMoneyFieldsTrait;
use DB;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasMoneyFieldsTrait;

    protected $hidden = ['deleted_at', 'created_at'];

    protected $appends = ['change_24h_percent', 'currency_type'];

    protected $fillable = [
        'name',
        'currency_id',
        'quote_currency_id',
        'currency_type',
        'currency_format_decimals',
        'quote_currency_type',
        'quote_currency_format_decimals',
        'min_trade_size',
        'min_trade_size_quote',
    ];

    const CURRENCY_TYPE = 1;
    const FIAT_CURRENCY_TYPE = 2;

    protected $cryptoFields = ['volume_24h', 'low_24h', 'high_24h', 'bid', 'ask', 'last', 'change_24h'];

    protected $cryptoQuoteFields = ['low_24h', 'high_24h', 'bid', 'ask', 'last', 'change_24h'];

    protected $decimalsFrom = '';

    public function quoteCurrency()
    {
        return $this->belongsTo(Currency::class, 'quote_currency_id');
    }

    public function quoteFiatCurrency()
    {
        return $this->belongsTo(FiatCurrency::class, 'quote_currency_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function fiatCurrency()
    {
        return $this->belongsTo(FiatCurrency::class, 'currency_id');
    }

    public function scopeBase($query, $base = null)
    {
        $query->where('is_active', 1);

        if ($base !== null) {
            $query->where('base_currency', $base);
        }

        return $query;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $this->addMoneyFieldToArray($array);

        return $array;
    }

    public function currencyDecimals()
    {
        return $this->isCurrencyFiat() ? 2 : $this->currency->decimals;
    }

    public function quoteCurrencyDecimals()
    {
        return $this->isQuoteCurrencyFiat() ? 2 : $this->quoteCurrency->decimals;
    }

    public function getChange24hPercentAttribute()
    {
        $oldPrice = MarketManager::get24PriceChange($this);

        $newPrice = MarketManager::getLastMarketPrice($this);

        if(!$oldPrice) {
        
            if(($newPrice && $newPrice->rate->getAmount() > 0)) {
            	return 100;
            }
        
            return 0;
        }
        
        return round((($newPrice->rate->getAmount() - $oldPrice->rate->getAmount()) / $oldPrice->rate->getAmount()) * 100, 2);
    }

    public function getCurrency() {
        return $this->currency_type == 1 ? $this->currency : $this->fiatCurrency;
    }

    public function getQuoteCurrency() {
        return $this->quote_currency_type == 1 ? $this->quoteCurrency : $this->quoteFiatCurrency;
    }
}

<?php

namespace App\Traits;

use App\Helpers\MoneyHelper;
use Illuminate\Support\Str;
use Money\Currency;
use Money\Money;

trait HasMoneyFieldsTrait
{
    /**
     * Override the __get magic method of eloquent model, for the key ending with _decimal and starting with a valid money/crypto field.
     *
     * Example, available_balance_decimal returns the decimal value of the of the available_balance Money attribute.
     *
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        if (Str::endsWith($key, '_decimal')) {
            $fieldKey = preg_replace('/'.preg_quote('_decimal', '/').'$/', '', $key);

            if ($this->moneyFields && in_array($fieldKey, $this->moneyFields)) {
                $money = $this->asMoney($this->getAttributeFromArray($fieldKey));

                return MoneyHelper::toDecimal($money);
            }

            if ($this->cryptoFields && in_array($fieldKey, $this->cryptoFields)) {
                $money = $this->asMoney($this->getAttributeFromArray($fieldKey), 'XBT');

                $type = '';

                if(isset($this->cryptoQuoteFields) && in_array($fieldKey, $this->cryptoQuoteFields)) {
                    $type = 'quote';
                }

                return MoneyHelper::toDecimal($money, $this->findDecimalsField($type));
            }
        }

        //Preserve the default value of the magic method
        return parent::__get($key);
    }

    public function getAttributeValue($key)
    {
        if ($this->moneyFields && in_array($key, $this->moneyFields)) {
            return $this->asMoney($this->getAttributeFromArray($key));
        }

        if ($this->cryptoFields && in_array($key, $this->cryptoFields)) {
            return $this->asMoney($this->getAttributeFromArray($key), 'XBT');
        }

        return parent::getAttributeValue($key);
    }

    public function setAttribute($key, $value)
    {
        if ($this->moneyFields && in_array($key, $this->moneyFields)) {
            $this->attributes[$key] = $this->fromMoney($value);

            return $this;
        }

        if ($this->cryptoFields && in_array($key, $this->cryptoFields)) {
            $this->attributes[$key] = $this->fromMoney($value);

            return $this;
        }

        return parent::setAttribute($key, $value);
    }

    private function fromMoney($value)
    {
        if ($value instanceof Money) {
            return intval($value->getAmount());
        }

        return $value;
    }

    private function asMoney($value, $currency = 'USD')
    {
        if (is_null($value)) {
            return;
        }
        if ($value instanceof Money) {
            return $value;
        }

        return new Money($value, new Currency($currency));
    }

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        if ($this->moneyFields) {
            foreach ($this->moneyFields as $field) {
                $value = $this->$field;
                if (! is_null($value)) {
                    $value = [
                        'amount' => $value->getAmount(),
                        'currency' => $value->getCurrency(),
                        'formatted' => $this->formatMoney($value),
                    ];
                }
                $attributes[$field] = $value;
            }
        }

        if ($this->cryptoFields) {
            foreach ($this->cryptoFields as $field) {
                $value = $this->$field;
                if (! is_null($value)) {
                    $value = [
                        'amount' => $value->getAmount(),
                        'currency' => $value->getCurrency(),
                        'formatted' => $this->formatMoney($value),
                    ];
                }
                $attributes[$field] = $value;
            }
        }

        return $attributes;
    }

    private function formatMoney($money)
    {
        // This is where you go from Money object to formatted text such as "$50"
        return $money->getCurrency()->getCode().' '.($money->getAmount() / 100);
    }

    protected function addMoneyFieldToArray(&$array)
    {
        if ($this->moneyFields) {
            foreach ($this->moneyFields as $money) {
                $array[$money] = MoneyHelper::toDecimal($this->$money);
            }
        }

        if ($this->cryptoFields) {
            foreach ($this->cryptoFields as $money) {
                $type = 'base';
                if(isset($this->cryptoQuoteFields) && in_array($money, $this->cryptoQuoteFields)) {
                    $type = 'quote';
                }
                $array[$money] = MoneyHelper::toDecimal($this->$money, $this->findDecimalsField($type));
            }
        }
    }

    public function isCurrencyFiat() {
        return $this->currency_type == 2;
    }

    public function isQuoteCurrencyFiat() {
        return $this->quote_currency_type == 2;
    }

    protected function findDecimalsField($type) {

        if($this->decimalsFrom === false) return $this->decimals;

        $decimalsFrom = explode('.', $this->decimalsFrom);

        $_this = $this;

        foreach ($decimalsFrom as $field) {
            if (!$field) break;
            $_this = $_this->$field;
        }

        $reflection = new \ReflectionClass($_this);
        if($reflection->getShortName() == "Market") {
            if($type == "quote") {
                return $_this->getQuoteCurrency()->decimals;
            } else {
                return $_this->getCurrency()->decimals;
            }

        }

        return $_this->currency ? $_this->currency->decimals : 8;
    }
}

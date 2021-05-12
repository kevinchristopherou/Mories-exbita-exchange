<?php

namespace App\Formatters;

use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;

/**
 * @author Frederik Bosch <f.bosch@genkgo.nl>
 */
final class CryptoCurrencies implements Currencies
{
    const CODE = 'XBT';

    const SYMBOL = "\xC9\x83";

    private $unit = 0;

    public function __construct($unit = 8)
    {
        $this->unit = $unit;
    }

    /**
     * {@inheritdoc}
     */
    public function contains(Currency $currency)
    {
        return self::CODE === $currency->getCode();
    }

    /**
     * {@inheritdoc}
     */
    public function subunitFor(Currency $currency)
    {
        if ($currency->getCode() !== self::CODE) {
            throw new UnknownCurrencyException(
                $currency->getCode().' is not bitcoin and is not supported by this currency repository'
            );
        }

        return $this->unit;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator([new Currency(self::CODE)]);
    }
}

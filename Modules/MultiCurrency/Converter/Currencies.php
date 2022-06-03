<?php


namespace Modules\MultiCurrency\Converter;


class Currencies
{
    private static $currencies = [
        'USD',
        "BGN",
        "NZD",
        "ILS",
        "RUB",
        "CAD",
        "PHP",
        "CHF",
        "AUD",
        "JPY",
        "TRY",
        "HKD",
        "MYR",
        "HRK",
        "CZK",
        "IDR",
        "DKK",
        "NOK",
        "HUF",
        "GBP",
        "MXN",
        "THB",
        "ISK",
        "ZAR",
        "BRL",
        "SGD",
        "PLN",
        "INR",
        "KRW",
        "RON",
        "CNY",
        "SEK",
        "EUR",

    ];

    public static function getCurrencies() {
        return self::$currencies;
    }
}
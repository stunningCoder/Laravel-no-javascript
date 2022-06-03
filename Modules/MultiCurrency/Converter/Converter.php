<?php


namespace Modules\MultiCurrency\Converter;

class Converter
{
    /**
     * Api URL for calls
     * @var string
     */
    protected $apiUrl = 'https://api.exchangeratesapi.io/latest?base=USD';

    /**
     * Time in minutes for how long are rates cached
     * @var int
     */
    protected $cacheTime = 2;

    public function getRates() {
        try {
            $rates = \Cache::remember('multicurrency_rates_array', $this->cacheTime, function () {
                $json = json_decode(file_get_contents($this->apiUrl), true);
                $ratesArray = $json['rates'];

                return $ratesArray;
            });

            return $rates;
        } catch (\Exception $e) {

            return null;
        }
    }

    public function convert($usdValue, $currencyName = 'USD') {
        try{
            $currency = strtoupper($currencyName);
            $rates = $this->getRates();
            $rate = $rates[$currency];
            return $usdValue*$rate;
        } catch (\Exception $e){
            return $usdValue;
        }
    }

    public function convertFromLocal($localValue, $currencyName = 'USD') {
        try{
            $currency = strtoupper($currencyName);
            $rates = $this->getRates();
            $rate = $rates[$currency];
            return $localValue/$rate;
        } catch (\Exception $e){
            return $localValue;
        }
    }

    public function getSupportedCurrencies(){

        return  Currencies::getCurrencies();
    }

}
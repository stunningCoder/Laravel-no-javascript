{{--{{ \App\Marketplace\Utility\CurrencyConverter::convertToLocal($usdValue) }}--}}
{{--{{ \App\Marketplace\Utility\CurrencyConverter::getSymbol(\App\Marketplace\Utility\CurrencyConverter::getLocalCurrency()) }}--}}
@php
    $userLocalCurrency = session()->get('user_local_currency');
@endphp
{{ \App\Marketplace\Utility\CurrencyConverter::getSymbol()}}{{$userLocalCurrency * $usdValue}}
{{\App\Marketplace\Utility\CurrencyConverter::getLocalCurrency()}}
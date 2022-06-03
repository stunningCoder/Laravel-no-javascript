<?php // Code within app\Helpers\Helper.php

namespace App\Http;
use Auth;
use Carbon\Carbon;

class Helper
{
    //if admin can enable markeplce multicurrency key true then exchange api will take over!
    //if admin can enable markeplce multicurrency key false then exchange default usd currency works!
    public static function convertCurrency($from, $to,$amount)
    {
        if(config('marketplace.multicurreny') == false){
            $usd = "1.0";
            return $usd;

        }else{

            $amount = $amount;

            $apikey = 'cb5b1653444d90917ee9';

            $from_Currency = urlencode($from);
            $to_Currency = urlencode($to);
            $query =  "{$from_Currency}_{$to_Currency}";

            //  change to the free URL if you're using the free version
            $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");

            $obj = json_decode($json, true);

            $val = $obj["$query"];

            $formatValue = number_format($val, 2, '.', '');

            $data = "$amount $from_Currency = $to_Currency $formatValue";

            return $formatValue ;
        }

    }

    public static function setUserLocalCurrencySession(){
        $user = Auth::user();
        //dd($user->local_currency);
        if(!empty($user->local_currency)){
            $userLocalCurrency = Self::convertCurrency("USD",$user->local_currency,1);
            session()->forget('user_local_currency');
            session()->put('user_local_currency',$userLocalCurrency);
        }else{
            $userLocalCurrency = Self::convertCurrency("USD","USD",1);
            session()->forget('user_local_currency');
            session()->put('user_local_currency',$userLocalCurrency);
        }
    }

    public static function getTimeLeft($date){
        $data = [];
        $s = \Carbon\Carbon::now()->diffInSeconds($date);
        $value = $s;
        $dt = \Carbon\Carbon::now();
        $days =    $dt->diffInDays($dt->copy()->addSeconds($value));
        $hours =   $dt->diffInHours($dt->copy()->addSeconds($value)->subDays($days));
        $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($value)->subDays($days)->subHours($hours));
        $seconds = $dt->diffInSeconds($dt->copy()->addSeconds($value)->subDays($days)->subHours($hours)->subMinutes($minutes));

        $data = [
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds'  => $seconds
        ];
        return $data;
    }

    public static function getTimeLefByHour($datecreated) {

        $ishours = true;
        $hours_days = 12;

        $startdate = Carbon::parse(Carbon::now())->toDateTimeString();
        $createdat = $datecreated;                         ////Created At

        $expirytime = Carbon::parse($createdat);
        $createdat = ($ishours ? $expirytime->addhour($hours_days)->toDateTimeString() : $expirytime->addDays($hours_days)->toDateTimeString());


        $d1 = Carbon::parse($startdate);
        $d2 = Carbon::parse($createdat);

        $shortdate1 = strtotime($startdate);
        $shortdate2 = strtotime($createdat);
        $totalSecondsDiff = ($shortdate2-$shortdate1);
        $interval = $d1->diff($d2);

        $difference = ($totalSecondsDiff < 0 ? -1 : 1);
        $Seconds_inText = ($interval->s > 1 ? " seconds, " : " second, ");
        $Minutes_inText = ($interval->i > 1 ? " minutes, " : " minute, ");
        $Hours_inText = ($interval->h > 1 ? " hours, " : " hour, ");
        $Days_inText = ($interval->d > 1 ? " days, " : " day, ");
        $Months_inText = ($interval->m > 1 ? " months, " : " month, ");
        $Years_inText = ($interval->y > 1 ? " years, " : " year, ");

        $diffInSeconds = ($interval->s == 0 ? "" : (($interval->s * $difference) . $Seconds_inText));
        $diffInMinutes = ($interval->i == 0 ? "" : (($interval->i * $difference) . $Minutes_inText));
        $diffInHours   = ($interval->h == 0 ? "" : (($interval->h * $difference) . $Hours_inText));
        $diffInDays    = ($interval->d == 0 ? "" : (($interval->d * $difference) . $Days_inText));
        $diffInMonths  = ($interval->m == 0 ? "" : (($interval->m * $difference) . $Months_inText));
        $diffInYears   = ($interval->y == 0 ? "" : (($interval->y * $difference) . $Years_inText));

        $finaldiff = ($diffInYears . $diffInMonths . $diffInDays . $diffInHours . $diffInMinutes . $diffInSeconds);
        $finaldiff = rtrim($finaldiff, ", ");

        return $finaldiff;
    }

    public static function getTimeLefByDays($datecreated) {

        $ishours = false;
        $hours_days = 14;

        $startdate = Carbon::parse(Carbon::now())->toDateTimeString();
        $createdat = $datecreated;                         ////Created At

        $expirytime = Carbon::parse($createdat);
        $createdat = ($ishours ? $expirytime->addhour($hours_days)->toDateTimeString() : $expirytime->addDays($hours_days)->toDateTimeString());


        $d1 = Carbon::parse($startdate);
        $d2 = Carbon::parse($createdat);

        $shortdate1 = strtotime($startdate);
        $shortdate2 = strtotime($createdat);
        $totalSecondsDiff = ($shortdate2-$shortdate1);
        $interval = $d1->diff($d2);

        $difference = ($totalSecondsDiff < 0 ? -1 : 1);
        $Seconds_inText = ($interval->s > 1 ? " seconds, " : " second, ");
        $Minutes_inText = ($interval->i > 1 ? " minutes, " : " minute, ");
        $Hours_inText = ($interval->h > 1 ? " hours, " : " hour, ");
        $Days_inText = ($interval->d > 1 ? " days, " : " day, ");
        $Months_inText = ($interval->m > 1 ? " months, " : " month, ");
        $Years_inText = ($interval->y > 1 ? " years, " : " year, ");

        $diffInSeconds = ($interval->s == 0 ? "" : (($interval->s * $difference) . $Seconds_inText));
        $diffInMinutes = ($interval->i == 0 ? "" : (($interval->i * $difference) . $Minutes_inText));
        $diffInHours   = ($interval->h == 0 ? "" : (($interval->h * $difference) . $Hours_inText));
        $diffInDays    = ($interval->d == 0 ? "" : (($interval->d * $difference) . $Days_inText));
        $diffInMonths  = ($interval->m == 0 ? "" : (($interval->m * $difference) . $Months_inText));
        $diffInYears   = ($interval->y == 0 ? "" : (($interval->y * $difference) . $Years_inText));

        $finaldiff = ($diffInYears . $diffInMonths . $diffInDays . $diffInHours . $diffInMinutes . $diffInSeconds);
        $finaldiff = rtrim($finaldiff, ", ");

        return $finaldiff;
    }

    public static function getTimeLeftByMinutes($date){
        $date = Carbon::parse($date);
        $now = Carbon::now();
        $diffinnewmin = $date->diffInMinutes($now);

        $ishours = true;
        $hours_days = $diffinnewmin;

        $startdate = Carbon::parse(Carbon::now())->toDateTimeString();
        $createdat = $date;                         ////Created At

        $expirytime = Carbon::parse($createdat);
        $createdat = ($ishours ? $expirytime->addMinute($hours_days)->toDateTimeString() : $expirytime->addDays($hours_days)->toDateTimeString());


        $d1 = Carbon::parse($startdate);
        $d2 = Carbon::parse($createdat);

        $shortdate1 = strtotime($startdate);
        $shortdate2 = strtotime($createdat);
        $totalSecondsDiff = ($shortdate2-$shortdate1);
        $interval = $d1->diff($d2);

        $difference = ($totalSecondsDiff < 0 ? -1 : 1);
        $Seconds_inText = ($interval->s > 1 ? " seconds, " : " second, ");
        $Minutes_inText = ($interval->i > 1 ? " minutes, " : " minute, ");
        $Hours_inText = ($interval->h > 1 ? " hours, " : " hour, ");
        $Days_inText = ($interval->d > 1 ? " days, " : " day, ");
        $Months_inText = ($interval->m > 1 ? " months, " : " month, ");
        $Years_inText = ($interval->y > 1 ? " years, " : " year, ");

        $diffInSeconds = ($interval->s == 0 ? "" : (($interval->s * $difference) . $Seconds_inText));
        $diffInMinutes = ($interval->i == 0 ? "" : (($interval->i * $difference) . $Minutes_inText));
        $diffInHours   = ($interval->h == 0 ? "" : (($interval->h * $difference) . $Hours_inText));
        $diffInDays    = ($interval->d == 0 ? "" : (($interval->d * $difference) . $Days_inText));
        $diffInMonths  = ($interval->m == 0 ? "" : (($interval->m * $difference) . $Months_inText));
        $diffInYears   = ($interval->y == 0 ? "" : (($interval->y * $difference) . $Years_inText));

        $finaldiff = ($diffInYears . $diffInMonths . $diffInDays . $diffInHours . $diffInMinutes . $diffInSeconds);
        $finaldiff = rtrim($finaldiff, ", ");

        return $finaldiff;
    }
}
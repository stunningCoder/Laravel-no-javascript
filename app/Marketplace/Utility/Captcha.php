<?php

namespace App\Marketplace\Utility;

use Gregwar\Captcha\CaptchaBuilder;


/**
 * Creating and Verifying Captcha
 */
class Captcha
{

    public static function Build()
    {

        $width = 200;
        $height = 50;
        $char_number = 6;
        $builder = new CaptchaBuilder($char_number);
        $builder->setDistortion(false);
        $builder->setBackgroundColor(255, 255, 255);
        $builder->setMaxBehindLines(1);
        $builder->setMaxFrontLines(2);
        $builder->setTextColor(mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
        $builder->build($width, $height);
        session()->put('captcha', $builder->getPhrase());

        return $builder->inline();
    }

    public static function Verify($input)
    {
        if (!session()->has('captcha')) {
            return false;
        }
        if (session()->get('captcha') !== $input) {
            return false;
        }

        return true;
    }
}

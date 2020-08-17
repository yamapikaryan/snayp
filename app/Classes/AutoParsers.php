<?php


namespace App\Classes;


class AutoParsers
{
    public static function getParser($url)
    {
        if (strpos($url, '223/purchase') !== false) {
            return new PublicPurchase($url);
        } else {
            return new Ea44Parser($url);
        }
    }
}

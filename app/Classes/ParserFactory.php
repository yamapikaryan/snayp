<?php


namespace App\Classes;


class ParserFactory
{
    public static function getParser($url)
    {
        if (strpos($url, '/223/purchase/public/purchase/info/common-info') !== false) {
            return new PublicPurchase($url);
        } elseif (strpos($url, 'info/lot-list') !== false) {
            return new PublicPurchasePrice($url);
        } elseif (strpos($url, 'info/journal') !== false) {
            return new PublicPurchaseJournal($url);
        } elseif (strpos($url, '/ea44/view/supplier-results') !== false) {
            return new Ea44Winner($url);
        } elseif (strpos($url, 'www.sberbank-ast') !== false) {
            return new SberGos($url);
        } elseif (strpos($url, 'utp.sberbank-ast') !== false) {
            return new SberUtp($url);
        } elseif (strpos($url, 'rts-tender.ru') !== false) {
            return new RtsMarket($url);
        } elseif (strpos($url, 'lsr.ru') !== false) {
            return new LrsMarket($url);
        } elseif (strpos($url, 'otc.ru') !== false) {
            return new OtcMarket($url);
        } elseif (strpos($url, 'agregatoreat.ru') !== false) {
            return new AgregatorEat($url);
        } elseif (strpos($url, 'tektorg.ru') !== false) {
            return new Tektorg($url);
        } elseif (strpos($url, 'roseltorg.ru') !== false) {
            return new Roseltorg($url);
        } elseif (strpos($url, 'zakazrf.ru') !== false) {
            return new ZakazRf($url);
        } elseif (strpos($url, 'b2b-center.ru') !== false) {
            return new B2b($url);
        } else {
            return new Ea44Parser($url);
        }

/*        if (strpos($url, '/ea44') !== false) {
            return new Ea44Parser($url);
        } elseif (strpos($url, '223/purchase') !== false) {
            return new PublicPurchase($url);
        }
        throw new \RuntimeException('Не удалось создать парсер под URL ' . $url);*/
    }
}

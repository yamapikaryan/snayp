<?php


namespace App\Classes;


use simple_html_dom;

class Roseltorg extends BaseParser
{
    public function parse(simple_html_dom $html)
    {
        preg_match('/procedure\/(\d+)/i', $this->url, $tmp);
        $eisNum = $tmp[1];
        if (strlen($eisNum) > 13) {
            $eisLink = 'https://zakupki.gov.ru/epz/order/notice/ea44/view/common-info.html?regNumber=' . $eisNum;
        } else {
            $eisLink = 'https://zakupki.gov.ru/223/purchase/public/purchase/info/common-info.html?regNumber=' . $eisNum;
        }


        if(!filter_var($eisLink, FILTER_VALIDATE_URL)){
            throw new \RuntimeException('Не удалось найти uRL zakupki.gov');
        }

        $auction = ParserFactory::getParser($eisLink)->get();
        dd($auction);


        return $auction;
    }

}

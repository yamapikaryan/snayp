<?php


namespace App\Classes;


use simple_html_dom;

class SberGos extends BaseParser
{
    public function parse(simple_html_dom $html)
    {


        preg_match('/<linkhref>(.+)<\/linkhref>/', $html, $tmp);


        if(!filter_var($tmp[1], FILTER_VALIDATE_URL)){
            throw new \RuntimeException('Не удалось найти uRL zakupki.gov');
        }

        $auction = ParserFactory::getParser($tmp[1])->get();


        return $auction;
    }

}

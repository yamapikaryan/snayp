<?php


namespace App\Classes;


use simple_html_dom;

class SberUtp extends BaseParser
{
    public function parse(simple_html_dom $html)
    {
        $auction = [
            'auctionNumber' => '',
            'isPriceRequest' => '',
            'is223fz' => '',
            'deadline' => '',
            'auctionDate' => '',
            'etpId' => '',
            'client' => '',
            'auctionObject' => '',
            'auctionStatus' => '',
            'auctionStatusName' => '',
            'maxPrice' => '',
        ];

        preg_match('/regNumber=(.+)"/U', $html, $tmp);

        if (!empty($tmp)) {
            $eisUrl = 'http://zakupki.gov.ru/223/purchase/public/purchase/info/common-info.html?regNumber=' . $tmp[1];
            if (!filter_var($eisUrl, FILTER_VALIDATE_URL)) {
                throw new \RuntimeException('Не удалось найти uRL zakupki.gov');
            }

            $auction = ParserFactory::getParser($eisUrl)->get();

            return $auction;
        } else {
            return [
                'auction' => $auction,
            ];
        }
    }
}

<?php


namespace App\Classes;

use GuzzleHttp\Client;
use simple_html_dom;
use Carbon\Carbon;


class OtcMarket extends BaseParser
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

        preg_match('/tender\/(\d+)/i', $this->url, $tmp);

        $baseUrl = 'https://otc.ru/microservices-otc/order/api/order/GetTender';

        $client = new Client();

        $response = $client->request('POST', $baseUrl, [
            'json' => ['TenderId' => (int)$tmp[1]]
        ]);

        $decodedResponse = json_decode((string)$response->getBody(), true);


//        $auction = [
//            'auctionNumber' => '',
//            'isPriceRequest' => '',
//            'is223fz' => '',
//            'deadline' => '',
//            'auctionDate' => '',
//            'etpId' => '',
//            'client' => '',
//            'auctionObject' => '',
//            'auctionStatus' => '',
//            'auctionStatusName' => '',
//            'maxPrice' => '',
//        ];


        preg_match('/regNumber=(\d+)/i', $decodedResponse['EtpUrl'], $eisNum);

        $isEis = count($eisNum);

        if ($isEis > 0) {
            $auction = ParserFactory::getParser($decodedResponse['EtpUrl'])->get();
            return $auction;

        } else {

            $auction['auctionNumber'] = $decodedResponse['TenderId'];

            if ($decodedResponse['PurchaseMethod'] == 3) {
                $auction['isPriceRequest'] = 1;
            } else {
                $auction['isPriceRequest'] = 0;
            }

            if (strpos($decodedResponse['EtpUrl'], '/223/') !== false) {
                $auction['is223fz'] = 1;
            } else {
                $auction['is223fz'] = 0;
            }

            $auction['deadline'] = trim(substr($decodedResponse['ApplicationEndDate'], 0, 10));
            if (!empty($auction['deadline'])) {
                $auction['deadline'] = Carbon::createFromFormat('Y-m-d', $auction['deadline'])->format('d.m.Y');
            } else {
                $auction['deadline'] = null;
            }


//            $auction['deadline'] = str_replace('-', '.', $date);

            $auction['auctionDate'] = $auction['deadline'];

            $auction['etpId'] = 5;

            $auction['client'] = $decodedResponse['Organizer']['Name'];

            $auction['auctionObject'] = $decodedResponse['TradeName'];

//            $auction['auctionStatus'] = $decodedResponse['TenderId'];

//            $auction['auctionStatusName'] = $decodedResponse['TenderId'];

            $auction['maxPrice'] = number_format($decodedResponse['Price'], 2, ',', ' ');

            return [
                'auction' => $auction,
            ];

        }
    }
}

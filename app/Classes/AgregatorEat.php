<?php


namespace App\Classes;


use simple_html_dom;
use GuzzleHttp\Client;
use Carbon\Carbon;

class AgregatorEat extends BaseParser
{
    public function parse(simple_html_dom $html)
    {
        preg_match('/purchase\/(\d+)/i', $this->url, $tmp);

        $baseUrl = 'https://agregatoreat.ru/api/els/purchase/loadOrderByID';

        $client = new Client();

        $response = $client->request('POST', $baseUrl, [
            'json' => ['id' => $tmp[1], 'routeName' => "purchase-order-info"]
        ]);

        $decodedResponse = json_decode((string)$response->getBody(), true);

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

        $auction['auctionNumber'] = $decodedResponse['items'][0]['registry_code'];



        $auction['isPriceRequest'] = 1;



        if ($decodedResponse['items'][0]['condition']['purchaseType']['law'] == "44") {
            $auction['is223fz'] = 0;
        } else {
            $auction['is223fz'] = 1;
        }

        $auction['deadline'] = trim(substr($decodedResponse['items'][0]['orderFinish'], 0, 10));
        if (!empty($auction['deadline'])) {
            $auction['deadline'] = Carbon::createFromFormat('Y-m-d', $auction['deadline'])->format('d.m.Y');
        } else {
            $auction['deadline'] = null;
        }


        $auction['auctionDate'] = $auction['deadline'];

        $auction['etpId'] = 8;

        $auction['client'] = $decodedResponse['items'][0]['condition']['organizationFullName'];

        $auction['auctionObject'] = $decodedResponse['items'][0]['condition']['nameTRY'];

//            $auction['auctionStatus'] = $decodedResponse['TenderId'];

//            $auction['auctionStatusName'] = $decodedResponse['TenderId'];

        $auction['maxPrice'] = number_format($decodedResponse['items'][0]['condition']['startfinalprice'], 2, ',', ' ');



        return [
            'auction' => $auction,
        ];
    }
}

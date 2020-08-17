<?php


namespace App\Classes;


use simple_html_dom;
use GuzzleHttp\Client;
use Carbon\Carbon;

class RtsMarket extends BaseParser
{
    public function parse(simple_html_dom $html)
    {
        // Проверяем аукцион или запрос
        preg_match('/rts-tender\.ru\/(.+)\//iU', $this->url, $localPlace);

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

        if ($localPlace[1] === 'zapros') {

            // Подготавливам данные запроса

            preg_match('/zapros\/(.+)\//iU', $this->url, $tmpNum);
            $auctionId = $tmpNum[1];
            $baseUrl = 'https://zmo-new-webapi.rts-tender.ru/market/api/v1/trades/' . $auctionId;

            $client = new Client();
            $response = $client->request('GET', $baseUrl);
            $decodedResponse = json_decode((string)$response->getBody(), true);

            $auction['auctionNumber'] = $auctionId;
            $auction['isPriceRequest'] = 1;
            $auction['is223fz'] = 1;
            $auction['etpId'] = 3;


            $auction['deadline'] = trim(substr($decodedResponse['data']['FillingApplicationEndDate'], 0, 10));
            if (!empty($auction['deadline'])) {
                $auction['deadline'] = Carbon::createFromFormat('Y-m-d', $auction['deadline'])->format('d.m.Y');
            } else {
                $auction['deadline'] = null;
            }
            $auction['auctionDate'] = $auction['deadline'];
            $auction['auctionObject'] = $decodedResponse['data']['TradeName'];
            $auction['client'] = $decodedResponse['data']['CustomerFullName'];
            $auction['maxPrice'] = $decodedResponse['data']['Price'];

            $auctionStatus = $decodedResponse['data']['MarketStateDescription'];
            if ($auctionStatus === 'Прием предложений') {
                $auction['auctionStatus'] = 2;
                $auction['auctionStatusName'] = 'Подача заявок';
            } elseif (($auctionStatus === 'Согласование условий') || ($auctionStatus === 'Согласование договора') || ($auctionStatus === 'Сделка завершена')) {
                $auction['auctionStatus'] = 6;
                $auction['auctionStatusName'] = 'Определение поставщика завершено';
            } elseif ($auctionStatus === 'Сделка завершена без договора') {
                $auction['auctionStatus'] = 7;
                $auction['auctionStatusName'] = 'Определение поставщика отменено';
            } else {
                $auction['auctionStatus'] = 1;
                $auction['auctionStatusName'] = 'Статус неизвестен';
            }

        } else {
            // Подготавливаем данные аукциона
            if (($pos = strpos($this->url, "=")) !== FALSE) {
                $number = substr($this->url, $pos + 1);
            } else {
                $number = null;
            }
            $auction['auctionNumber'] = $number;

            $auction['isPriceRequest'] = 1;
            $auction['is223fz'] = 1;
            $auction['etpId'] = 3;

            $blocks = $html->find('.info-table tr');

            if (!empty($blocks)) {
                foreach ($blocks as $block) {
                    $blockTitle = $block->find('td label', 0);
                    $blockContent = $block->find('td', 1);
                    $nameBlock = $block->find('td a', 0);

                    if (isset($blockTitle)) {
                        $blockTitleText = $blockTitle->innertext;
                    }
                    if (isset($blockContent)) {
                        $blockContentText = $blockContent->innertext;
                    }
                    if (isset($nameBlock)) {
                        $nameBlockText = $nameBlock->innertext;
                    }


                    if ($blockTitleText == 'НМЦК, руб.') {
                        $priceSp = trim(htmlspecialchars_decode($blockContentText));
                        $auction['maxPrice'] = str_replace("&#160;", " ", $priceSp);
                    } elseif ($blockTitleText == 'Дата окончания подачи предложений') {
                        $auction['deadline'] = substr(trim($blockContentText), 0, 10);
                    } elseif ($blockTitleText == 'Полное наименование') {
                        $auction['client'] = htmlspecialchars_decode($nameBlockText);
                    } elseif ($blockTitleText == 'Наименование') {
                        $auction['auctionObject'] = trim($blockContentText);
                    } elseif ($blockTitleText == 'Статус') {

                        if (trim($blockContentText) === 'Прием предложений') {
                            $auction['auctionStatus'] = 2;
                            $auction['auctionStatusName'] = 'Подача заявок';
                        } elseif (trim($blockContentText) === 'Рассмотрение предложений') {
                            $auction['auctionStatus'] = 3;
                            $auction['auctionStatusName'] = 'Работа комиссии';
                        } elseif (trim($blockContentText) === 'Завершена') {
                            $auction['auctionStatus'] = 4;
                            $auction['auctionStatusName'] = 'Закупка завершена';
                        } elseif (trim($blockContentText) === 'Отменена') {
                            $auction['auctionStatus'] = 5;
                            $auction['auctionStatusName'] = 'Закупка отменена';
                        } elseif (trim($blockContentText) === 'Заключение контрактов') {
                            $auction['auctionStatus'] = 6;
                            $auction['auctionStatusName'] = 'Определение поставщика завершено';
                        } elseif (trim($blockContentText) === 'Не состоялась') {
                            $auction['auctionStatus'] = 7;
                            $auction['auctionStatusName'] = 'Определение поставщика отменено';
                        } else {
                            $auction['auctionStatus'] = 1;
                            $auction['auctionStatusName'] = 'Статус неизвестен';
                        }
                    }

                }

                if (empty($auction['winner'])) {
                    $auction['winner'] = 'Нет данных';
                }

                if (empty($auction['winnerPrice'])) {
                    $auction['winnerPrice'] = NULL;
                }

                if (empty($auction['deadline'])) {
                    $auction['deadline'] = NULL;
                }

                $auction['auctionDate'] = $auction['deadline'];

            }
        }
        return [
            'auction' => $auction,
        ];
    }
}

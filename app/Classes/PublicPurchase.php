<?php


namespace App\Classes;


use simple_html_dom;

class PublicPurchase extends BaseParser
{
    public function parse(simple_html_dom $html)
    {

        $auction['is223fz'] = 1;

        $blocks = $html->find('.noticeTabBox.padBtm20 tr');


        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $spanTitle = $block->find('span', 0);
                $spanContent = $block->find('span', 1);
                $spanTime = $block->find('span', -2);

                $noSpanTitle = $block->find('td', 0);
                $noSpanContent = $block->find('td', 1);
                $noSpanContentLink =  $block->find('td a', -1);

                if (isset($spanTitle)) {
                    $spanTitleText = $spanTitle->innertext;
                }
                if (isset($spanContent)) {
                    $spanContentText = $spanContent->innertext;
                }
                if (isset($spanTime)) {
                    $spanTimeText = substr(trim($spanTime->innertext), 0, 10);
                }
                if (isset($noSpanTitle)) {
                    $noSpanTitleText = $noSpanTitle->innertext;
                }
                if (isset($noSpanContent)) {
                    $noSpanContentText = $noSpanContent->innertext;
                }
                if (isset($noSpanContentLink)) {
                    $noSpanContentLinkText = $noSpanContentLink->innertext;
                }



                if ($spanTitleText === 'Реестровый номер извещения') {
                    $auction['auctionNumber'] = trim($spanContentText);
                }  elseif ($spanTitleText == 'Наименование закупки') {
                    $auction['auctionObject'] = trim($spanContentText);
                } elseif ($spanTitleText == 'Способ размещения закупки') {
                    if (strpos(mb_strtolower(trim($spanContentText)), 'запрос') !== false) {
                        $auction['isPriceRequest'] = 1;} else {
                        $auction['isPriceRequest'] = 0;
                    }
                } elseif (strpos($spanTitleText, 'окончания подачи заявок') !== false) {
                    $auction['deadline'] = substr(trim($spanTimeText), 0, 10);
                } elseif (strpos($spanTitleText, 'Дата подведения итогов') !== false) {
                    if (preg_match('/^\d/', $spanTimeText)) {
                        $auction['auctionDate'] = $spanTimeText;
                    } else {
                        $auction['auctionDate'] = null;
                    }
                } elseif ($noSpanTitleText === "Наименование электронной площадки в информационно-телекоммуникационной сети «Интернет»") {
                    if (trim($noSpanContentText) === 'АКЦИОНЕРНОЕ ОБЩЕСТВО «ЕДИНАЯ ЭЛЕКТРОННАЯ ТОРГОВАЯ ПЛОЩАДКА»') {
                        $auction['etpId'] = 2;
                    } elseif (trim($noSpanContentText) === 'РТС-тендер') {
                        $auction['etpId'] = 3;
                    } elseif (trim($noSpanContentText) === 'ЗАКРЫТОЕ АКЦИОНЕРНОЕ ОБЩЕСТВО «СБЕРБАНК - АВТОМАТИЗИРОВАННАЯ СИСТЕМА ТОРГОВ»') {
                        $auction['etpId'] = 4;
                    } elseif (trim(htmlspecialchars_decode($noSpanContentText)) === 'ЭТП "OTC.ru"') {
                        $auction['etpId'] = 5;
                    } elseif (trim(htmlspecialchars_decode($noSpanContentText)) === 'Национальная электронная площадка') {
                        $auction['etpId'] = 7;
                    } elseif (trim(htmlspecialchars_decode($noSpanContentText)) === 'АКЦИОНЕРНОЕ ОБЩЕСТВО "ТЭК-ТОРГ"') {
                        $auction['etpId'] = 9;
                    } elseif (trim(htmlspecialchars_decode($noSpanContentText)) === 'АКЦИОНЕРНОЕ ОБЩЕСТВО "АГЕНТСТВО ПО ГОСУДАРСТВЕННОМУ ЗАКАЗУ РЕСПУБЛИКИ ТАТАРСТАН"') {
                        $auction['etpId'] = 10;
                    } elseif (trim(htmlspecialchars_decode($noSpanContentText)) === 'АО «Сбербанк-АСТ» (УТП)') {
                        $auction['etpId'] = 11;
                    } else {
                        $auction['etpId'] = 1;
                    }
                } elseif ($noSpanTitleText === 'Наименование организации') {
                    $auction['client'] = htmlspecialchars_decode($noSpanContentLinkText);
                }


                try {
                    $priceUrl = str_replace("common-info","lot-list", $this->url);
                    $priceParser = new PublicPurchasePrice($priceUrl);
                    $priceData = $priceParser->get();

                    if (!empty($priceData['price'])) {
                        $auction['maxPrice'] = $priceData['price'];
                    }
                }catch(\Throwable $ex){
                    // do nothing
                }

                try {
                    $journalUrl = str_replace("common-info","journal", $this->url);
                    $journalParser = new PublicPurchaseJournal($journalUrl);
                    $journalData = $journalParser->get();

                    if (!empty($journalData['status'])) {
                        $auction['auctionStatus'] = $journalData['status'];
                    }
                }catch(\Throwable $ex){
                    // do nothing
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

            }

        }

        return [
            'auction' => $auction,
        ];
    }


}

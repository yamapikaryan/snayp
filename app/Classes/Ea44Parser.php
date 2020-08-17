<?php


namespace App\Classes;


use simple_html_dom;

class Ea44Parser extends BaseParser
{
    public function parse(simple_html_dom $html)
    {


        //подготавливаем номер аукциона
        $auctionNumberRaw = $html->find('.cardMainInfo__purchaseLink.distancedText a', -1);
        if (empty($auctionNumberRaw)) {
            $auctionNumber = null;
        }
        $auction['auctionNumber'] = trim(str_replace('№ ', '', $auctionNumberRaw->innertext));

        $auctionTypeRaw = $html->find('.cardMainInfo__title.distancedText', -1);
        $auctionType = mb_strtolower(trim($auctionTypeRaw->innertext));
        if (strpos($auctionType, 'запрос') !== false) {
            $isPriceRequest = 1;
        } else {
            $isPriceRequest = 0;
        }
        $auction['isPriceRequest'] = $isPriceRequest;
        $auction['is223fz'] = 0;


        $blocks = $html->find('.blockInfo__section');

        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $blockTitle = $block->find('.section__title', -1);
                $blockContent = $block->find('.section__info', -1);
                $blockContentLink = $block->find('.section__info a', -1);
                if (empty($blockTitle) || empty($blockContent)) {
                    continue;
                }
                $blockTitleText = $blockTitle->innertext;


                if ($blockTitleText === 'Наименование электронной площадки в информационно-телекоммуникационной сети "Интернет"') {
                    if (trim($blockContent->innertext) === 'АО «ЕЭТП»') {
                        $auction['etpId'] = 2;
                    } elseif (trim($blockContent->innertext) === 'РТС-тендер') {
                        $auction['etpId'] = 3;
                    } elseif ((trim($blockContent->innertext) === 'ЗАО «Сбербанк-АСТ»') || (trim($blockContent->innertext) === 'АО «Сбербанк-АСТ»')) {
                        $auction['etpId'] = 4;
                    } elseif (trim($blockContent->innertext) === 'ЭТП "OTC.ru"') {
                        $auction['etpId'] = 5;
                    } elseif (trim($blockContent->innertext) === 'Национальная электронная площадка') {
                        $auction['etpId'] = 7;
                    } elseif (trim($blockContent->innertext) === 'ЭТП ТЭК-Торг') {
                        $auction['etpId'] = 9;
                    } elseif (trim($blockContent->innertext) === 'АГЗ РТ') {
                        $auction['etpId'] = 10;
                    } elseif (trim($blockContent->innertext) === 'ЭТП Газпромбанк') {
                        $auction['etpId'] = 12;
                    } else {
                        $auction['etpId'] = 1;
                    }
                } elseif ($blockTitleText === 'Размещение осуществляет') {
                    $auction['client'] = trim($blockContentLink->innertext);
                } elseif ($blockTitleText === 'Наименование объекта закупки') {
                    $auction['auctionObject'] = trim($blockContent->innertext);
                } elseif ($blockTitleText === 'Этап закупки') {

                    if (trim($blockContent->innertext) === 'Подача заявок') {
                        $auction['auctionStatus'] = 2;
                        $auction['auctionStatusName'] = 'Подача заявок';
                    } elseif (trim($blockContent->innertext) === 'Работа комиссии') {
                        $auction['auctionStatus'] = 3;
                        $auction['auctionStatusName'] = 'Работа комиссии';
                    } elseif (trim($blockContent->innertext) === 'Закупка завершена') {
                        $auction['auctionStatus'] = 4;
                        $auction['auctionStatusName'] = 'Закупка завершена';
                    } elseif (trim($blockContent->innertext) === 'Закупка отменена') {
                        $auction['auctionStatus'] = 5;
                        $auction['auctionStatusName'] = 'Закупка отменена';
                    } elseif (trim($blockContent->innertext) === 'Определение поставщика завершено') {
                        $auction['auctionStatus'] = 6;
                        $auction['auctionStatusName'] = 'Определение поставщика завершено';
                    } elseif (trim($blockContent->innertext) === 'Определение поставщика отменено') {
                        $auction['auctionStatus'] = 7;
                        $auction['auctionStatusName'] = 'Определение поставщика отменено';
                    } else {
                        $auction['auctionStatus'] = 1;
                        $auction['auctionStatusName'] = 'Статус неизвестен';
                    }


                } elseif (strpos($blockTitleText, 'Дата и время окончания срока подачи заявок') !== false) {
                    if ($isPriceRequest == 1) {
                        $auction['deadline'] = trim(substr($blockContent->innertext, 0, strpos($blockContent->innertext, "в")));
                    } else {
                        $auction['deadline'] = trim(substr($blockContent->innertext, 0, -6));
                    }
                } elseif ($blockTitleText === 'Дата проведения аукциона в электронной форме') {
                    $auction['auctionDate'] = trim($blockContent->innertext);
                } elseif (strpos($blockTitleText, 'Начальная (максимальная) цена') !== false) {
                    $auction['maxPrice'] = trim($blockContent->innertext);
                }

                try {
                    $winnerUrl = str_replace("common-info","supplier-results", $this->url);
                    $winnerParser = new Ea44Winner($winnerUrl);
                    $winnerData = $winnerParser->get();

                    if (!empty($winnerData['winner'])) {
                        $auction['winner'] = $winnerData['winner'];
                    }
                    else {$auction['winner'] = 'Нет данных';}

                    if (!empty($winnerData['winnerPrice'])) {
                        $auction['winnerPrice'] = $winnerData['winnerPrice'];
                    }
                    else {$auction['winnerPrice'] = 'Нет данных';}

                }catch(\Throwable $ex){
                    // do nothing
                }

                if (empty($auction['deadline'])) {
                    $auction['deadline'] = null;
                }

                if (empty($auction['auctionDate'])) {
                    $auction['auctionDate'] = $auction['deadline'];
                }

            }

        }

        return [
            'auction' => $auction,
        ];
    }
}

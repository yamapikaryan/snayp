<?php


namespace App\Classes;


use simple_html_dom;

class StatusParser extends BaseParser
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
        $auctionType = strtolower(trim($auctionTypeRaw->innertext));
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
                    } elseif (trim($blockContent->innertext) === 'ЗАО «Сбербанк-АСТ»') {
                        $auction['etpId'] = 4;
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
                    } elseif (trim($blockContent->innertext) === 'Работа комиссии') {
                        $auction['auctionStatus'] = 3;
                    } elseif (trim($blockContent->innertext) === 'Закупка завершена') {
                        $auction['auctionStatus'] = 4;
                    } elseif (trim($blockContent->innertext) === 'Закупка отменена') {
                        $auction['auctionStatus'] = 5;
                    } else {
                        $auction['auctionStatus'] = 1;
                    }


                } elseif ($blockTitleText === 'Дата и время окончания срока подачи заявок на участие в электронном аукционе') {
                    $auction['deadline'] = trim(substr($blockContent->innertext, 0, -6));
                } elseif ($blockTitleText === 'Дата проведения аукциона в электронной форме') {
                    $auction['auctionDate'] = trim($blockContent->innertext);
                } elseif ($blockTitleText === 'Начальная (максимальная) цена контракта') {
                    $auction['maxPrice'] = trim($blockContent->innertext);
                }


            }


        }
        return [
            'auction' => $auction,
        ];
    }
}

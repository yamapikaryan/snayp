<?php


namespace App\Classes;


use simple_html_dom;

class Tektorg extends BaseParser
{
    public function parse(simple_html_dom $html)
    {

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

        $auctionObject = $html->find('.procedure__item-name', 0);
        $auction['auctionObject'] = $auctionObject->innertext;
        $auction['etpId'] = 9;

        preg_match('/tektorg\.ru\/(.+)\//iU', $this->url, $localPlace);

        if ($localPlace[1] === "44-fz") {
            $auc44blocks = $html->find('.procedure__item-table tr');
            if (!empty($auc44blocks)) {
                foreach ($auc44blocks as $auc44block) {
                    $block44Title = $auc44block->find('td', 0);
                    $block44Number = $auc44block->find('td', 1);
                    if ($block44Title->innertext === 'Номер закупки:') {
                        $eisLink = "https://zakupki.gov.ru/epz/order/notice/ea44/view/common-info.html?regNumber=" . $block44Number->innertext;
                        break;

                    }
                }
            }
        } elseif ($localPlace[1] === "223-fz") {
            $auction['is223fz'] = 1;
            $auc223blocks = $html->find('.procedure__item-table tr');
            if (!empty($auc223blocks)) {
                foreach ($auc223blocks as $auc223block) {
                    $block223Title = $auc223block->find('td', 0);
                    $block223Content = $auc223block->find('td', 1);
                    if (trim($block223Title->innertext) === 'Реестровый номер закупки в ЕИС:') {
                        $eisLink = "https://zakupki.gov.ru/223/purchase/public/purchase/info/common-info.html?regNumber=" . $block223Content->innertext;
                        break;

                    } elseif (trim($block223Title->innertext) === 'Номер закупки:') {
                        $auction['auctionNumber'] = trim($block223Content->innertext);
                    } elseif (trim($block223Title->innertext) === 'Способ закупки:') {
                        $auctionType = mb_strtolower(trim($block223Content->innertext));
                        if (strpos($auctionType, 'запрос') !== false) {
                            $isPriceRequest = 1;
                        } else {
                            $isPriceRequest = 0;
                        }
                        $auction['isPriceRequest'] = $isPriceRequest;

                    } elseif (trim($block223Title->innertext) === 'Дата окончания приема заявок:') {
                        $auction['deadline'] = trim(substr($block223Content->innertext, 0, 10));
                    } elseif (trim($block223Title->innertext) === 'Подведение итогов не позднее:') {
                        $auction['auctionDate'] = trim(substr($block223Content->innertext, 0, 10));
                    } elseif (trim($block223Title->innertext) === 'Наименование организатора:') {
                        $auction['client'] = trim($block223Content->innertext);
                    } elseif (trim($block223Title->innertext) === 'Текущая стадия:') {
                        if (trim($block223Content->innertext) === 'Прием заявок.') {
                            $auction['auctionStatus'] = 2;
                            $auction['auctionStatusName'] = 'Подача заявок';
                        } elseif (trim($block223Content->innertext) === 'Работа комиссии.') {
                            $auction['auctionStatus'] = 3;
                            $auction['auctionStatusName'] = 'Работа комиссии';
                        } elseif (trim($block223Content->innertext) === 'Архив.') {
                            $auction['auctionStatus'] = 4;
                            $auction['auctionStatusName'] = 'Закупка завершена';
                        } elseif (trim($block223Content->innertext) === 'Отменён.') {
                            $auction['auctionStatus'] = 5;
                            $auction['auctionStatusName'] = 'Закупка отменена';
                        }
                    } elseif (trim($block223Title->innertext) === 'Начальная цена:') {
                        $auction['maxPrice'] = trim(substr($block223Content->innertext, 0, -3));
                    }
                }
            }

        } else {
            $auction['is223fz'] = 1;
            $auction['isPriceRequest'] = 1;
            $otherBlocks = $html->find('.procedure__item-table tr');
            if (!empty($otherBlocks)) {
                foreach ($otherBlocks as $otherBlock) {
                    $otherBlockTitle = $otherBlock->find('td', 0);
                    $otherBlockContent = $otherBlock->find('td', 1);

                    if (trim($otherBlockTitle->innertext) === 'Номер закупки:') {
                        $auction['auctionNumber'] = trim($otherBlockContent->innertext);
                    } elseif (trim($otherBlockTitle->innertext) === 'Дата окончания приема заявок:') {
                        $auction['deadline'] = trim(substr($otherBlockContent->innertext, 0, 10));
                        $auction['auctionDate'] = $auction['deadline'];
                    } elseif (trim($otherBlockTitle->innertext) === 'Наименование организатора:') {
                        $auction['client'] = trim($otherBlockContent->innertext);
                    } elseif (trim($otherBlockTitle->innertext) === 'Начальная цена:') {
                        $auction['maxPrice'] = trim(substr($otherBlockContent->innertext, 0, -3));
                    }
                }
            }
        }

        if (!empty($eisLink)) {
            $auction = ParserFactory::getParser($eisLink)->get();
            return $auction;
        } else {
            return [
                'auction' => $auction,
            ];
        }

    }
}

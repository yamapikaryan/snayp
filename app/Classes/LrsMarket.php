<?php


namespace App\Classes;


use simple_html_dom;

class LrsMarket extends BaseParser
{
    public function parse(simple_html_dom $html)
    {
        //подготавливаем номер аукциона

        $auction['isPriceRequest'] = 1;
        $auction['is223fz'] = 1;
        $auction['etpId'] = 6;


        $blocks = $html->find('.control-group.row-fluid');

        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $blockLabel = $block->find('label', 0);
                if (!empty($blockLabel)) {


                    $blockTitleText = $blockLabel->getAttribute('for');

                    $blockContent = $block->find('.span8 .control-readonly', 0);
                    if (isset($blockContent)) {
                        $blockContentText = $blockContent->innertext;
                    }


                    if ($blockTitleText == 'TenderNumber') {
                        $auction['auctionNumber'] = trim($blockContentText);
                    } elseif ($blockTitleText == 'Fields_Title') {
                        $auction['auctionObject'] = trim($blockContentText);
                    } elseif ($blockTitleText == 'Fields_Title') {
                        $auction['auctionObject'] = trim($blockContentText);
                    } elseif ($blockTitleText == 'Fields_RequestReceivingEndDate') {
                        $auction['deadline'] = substr(trim($blockContentText), 0, 10);
                    } elseif ($blockTitleText == 'Fields_CustomerUid') {
                        $auction['client'] = strip_tags(trim($blockContentText));


                    } elseif ($blockTitleText == 'StatusName') {

                        if (trim($blockContentText) === 'Извещение опубликовано') {
                            $auction['auctionStatus'] = 2;
                            $auction['auctionStatusName'] = 'Подача заявок';
                        } elseif (trim($blockContentText) === 'Прием заявок') {
                            $auction['auctionStatus'] = 2;
                            $auction['auctionStatusName'] = 'Подача заявок';
                        } elseif (trim($blockContentText) === 'Подведение итогов') {
                            $auction['auctionStatus'] = 3;
                            $auction['auctionStatusName'] = 'Работа комиссии';
                        } elseif (trim($blockContentText) === 'Завершен') {
                            $auction['auctionStatus'] = 4;
                            $auction['auctionStatusName'] = 'Закупка завершена';
                        } elseif (trim($blockContentText) === 'Отменен') {
                            $auction['auctionStatus'] = 5;
                            $auction['auctionStatusName'] = 'Закупка отменена';
                        } elseif (trim($blockContentText) === 'Приостановлен') {
                            $auction['auctionStatus'] = 5;
                            $auction['auctionStatusName'] = 'Закупка отменена';
                        } else {
                            $auction['auctionStatus'] = 1;
                            $auction['auctionStatusName'] = 'Статус неизвестен';
                        }
                    }




//
//                    }
//                }
                    else {
                        continue;
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


            return [
                'auction' => $auction,
            ];
        }
    }
}

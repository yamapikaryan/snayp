<?php


namespace App\Classes;


use simple_html_dom;

class ZakazRf extends BaseParser
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

        if (str_contains($this->url, '223etp')) {
            // 223-фз
            $blocks = $html->find('.details-table tr');
            if (!empty($blocks)) {
                foreach ($blocks as $block) {
                    $blockTitle = $block->find('td', 0);
                    $blockContent = $block->find('td', 1);
                    if (empty($blockTitle) || empty($blockContent)) {
                        continue;
                    }
                    if ($blockTitle->innertext === 'Номер извещения') {
                        $link = $blockContent->find('a', 0)->href;

                        if(!filter_var($link, FILTER_VALIDATE_URL)){
                            throw new \RuntimeException('Не удалось найти uRL zakupki.gov');
                        }

                        $auction = ParserFactory::getParser($link)->get();


                        return $auction;
                    }

                }
            }

        } else {
            // 44-фз
            $blocks = $html->find('.details-table tr');
            if (!empty($blocks)) {
                foreach ($blocks as $block) {
                    $blockTitle = $block->find('td', 0);
                    $blockContent = $block->find('td', 1);
                    if (empty($blockTitle) || empty($blockContent)) {
                        continue;
                    }
                    if ($blockTitle->innertext === 'Ссылка в ЕИС') {
                        $link = $blockContent->find('a', 0)->innertext;

                        if(!filter_var($link, FILTER_VALIDATE_URL)){
                            throw new \RuntimeException('Не удалось найти uRL zakupki.gov');
                        }

                        $auction = ParserFactory::getParser($link)->get();

                        return $auction;
                    }

                }
            }

        }

    }

}

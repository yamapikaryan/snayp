<?php


namespace App\Classes;


use simple_html_dom;

class PublicPurchaseJournal extends BaseParser
{
    public function parse(simple_html_dom $html)
    {

        $block = $html->find('.journalEvent', 0);


        if (isset($block)) {
            $blockText = $block->innertext;
        }


        if (strpos($blockText, 'Проведение закупки отменено') !== false) {
            $status = 5;
        } elseif (strpos($blockText, 'Закупка переведена на этап «Работа комиссии»') !== false) {
            $status = 3;
        } elseif (strpos($blockText, 'Закупка переведена на этап «Размещение завершено»') !== false) {
            $status = 4;
        } else {
            $status = 1;
        }

        return [
            'status' => $status,
        ];
    }


}

<?php


namespace App\Classes;


use simple_html_dom;

class PublicPurchasePrice extends BaseParser
{
    public function parse(simple_html_dom $html)
    {

        $block = $html->find('#lot td', 3);


        if (isset($block)) {
            $blockText = $block->innertext;
        }


        preg_match('~:(.*?)Р~', $blockText, $rawPrice);
        $price = preg_replace("!&nbsp;!", "", (trim($rawPrice[1])));


        return [
            'price' => $price,
        ];
    }


}

<?php


namespace App\Classes;


use simple_html_dom;

class Ea44Winner extends BaseParser
{
    public function parse(simple_html_dom $html)
    {

        $winnerBlock = $html->find('.tableBlock__body .tableBlock__col', 2);
        $winnerPriceBlock = $html->find('.tableBlock__body .tableBlock__col', 3);

        if (isset($winnerBlock)) {
            $winner = preg_replace("@\(.*?\)@", "", (trim($winnerBlock->innertext)));
        }


        if (isset($winnerPriceBlock)) {
            $untrimmedPrice = str_replace("<br>", "", $winnerPriceBlock->innertext);
            $winnerPrice = trim($untrimmedPrice);
        }


        return [
            'winner' => $winner,
            'winnerPrice' => $winnerPrice,
        ];


    }


}

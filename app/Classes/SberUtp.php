<?php


namespace App\Classes;


use simple_html_dom;

class SberUtp extends BaseParser
{
    public function parse(simple_html_dom $html)
    {


        #xmlData $this->html


        preg_match('/name="xmlData"\svalue="(.+)"/i', $this->html, $tmp);

        file_put_contents(storage_path('sber2.htm'), json_encode($tmp[1]));

        $xml = simplexml_load_string($tmp[1], "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        dd($array['CustomerInfo']['customernickname']);

        $tmp = $html->find ('#xmlData', 0);
        dd(empty($tmp));

            preg_match('/<linkhref>(.+)<\/linkhref>/', $auctionNumber->value, $tmp);

        if(!filter_var($tmp[1], FILTER_VALIDATE_URL)){
            throw new \RuntimeException('Не удалось найти uRL zakupki.gov');
        }

        $result = ParserFactory::getParser($tmp[1])->get();

        dd($result);

        $blocks = $html->find('tr');

        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                $blockLabel = $block->find('td', 0);
                $blockContent = $block->find('td', 1);

                if (trim($blockLabel->innertext) === 'Номер извещения') {
                    $eisNumber = $blockContent->innertext;
                }

            }

            dd($eisNumber);

        }
//
//        return [
//            'auction' => $auction,
//        ];
    }
}

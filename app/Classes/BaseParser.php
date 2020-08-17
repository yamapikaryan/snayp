<?php


namespace App\Classes;


use GuzzleHttp\Client;
use simple_html_dom;

abstract class BaseParser
{
    const DEFAULT_TIMEOUT = 3000000;

    const ATTEMPTS = 3;

    /** @var Client */
    protected $client;

    protected $url = '';

    protected $html = '';

    public function __construct($url)
    {
        $this->url = $url;
        include_once 'simple_html_dom.php';
    }

    protected function getClient()
    {
        if (empty($this->client)) {
            $this->client = new Client([
                'headers' => [
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                    'Cache-Control' => 'max-age=0',
                    'Connection' => 'keep-alive',
                    'Host' => 'zakupki.gov.ru',
                    'Sec-Fetch-Dest' => 'document',
                    'Sec-Fetch-Mode' => 'navigate',
                    'Sec-Fetch-Site' => 'none',
                    'Sec-Fetch-User' => '?1',
                    'Upgrade-Insecure-Requests' => '1',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.116 Safari/537.36',
                ]
            ]);
        }

        return $this->client;
    }


    public function get()
    {
        for ($i = 0; $i < self::ATTEMPTS; $i++) {
            try {
                $response = $this->getClient()->get($this->url);
                $this->html = html_entity_decode((string)$response->getBody());

//                dd($this->html);

                $html = str_get_html($this->html);

                // если удалось получить и разобрать HTML, больше получать страницу не нужно
                if (!empty($html)) {
                    break;
                }
            } catch (\Throwable|\Exception $ex) {
                echo 'Попытка №' . $i . ' провалилась, error: ' . $ex->getMessage() . "\n";
                usleep(self::DEFAULT_TIMEOUT);
            }
        }

        if (empty($html)) {
            throw new \RuntimeException('Не удалось разобрать HTML страницы ' . $this->url);
        }

        return $this->parse($html);
    }

    abstract public function parse(simple_html_dom $html);
}

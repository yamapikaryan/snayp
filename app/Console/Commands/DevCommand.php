<?php

namespace App\Console\Commands;

use App\Classes\BaseParser;
use App\Classes\ParserFactory;
use App\Classes\StatusParser;
use App\Classes\Ea44Parser;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;


class DevCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:launch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dev command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = 'https://utp.sberbank-ast.ru/RussianPost/NBT/PurchaseView/8/0/0/633581';

        ParserFactory::getParser($url)->get();

//        $contents = file_get_contents($url);
//
//        preg_match('/tender\/(\d+)/i', $url, $tmp);
//
//        dd((int)$tmp[1]);

//        dd(strpos($contents, 'tenderId'));

//        ParserFactory::getParser($url)->get();

    }
}

<?php

namespace App\Console\Commands;

use App\Auction;
use App\Classes\ParserFactory;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Mailgun\Mailgun;


class WatcherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watcher:launch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auction watcher';

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
        \Log::info('step 1');

        $mgClient = Mailgun::create(env('MAILGUN_API_KEY'), 'https://api.eu.mailgun.net/v3/mail.snayp.ru');
        $domain = env('MAILGUN_DOMAIN');

        \Log::info('step 2');

        $auctions = Auction::orderBy('id', 'DESC')->get();

        if ($auctions->isEmpty()) {
            $this->output('Аукционов нет');
            exit(0);
        }

        $users = User::get()->keyBy('id');

        \Log::info('step 3');

        $mailChanges = [
            'cancelled' => [],
            'completed' => [],
            'tomorrow' => [],
            'delayed' => [],
        ];

        $currentDate = isset($_GET['date']) ? $_GET['date'] : date('d.m.Y');
        $tomorrowDate = date('d.m.Y', strtotime($currentDate . ' +1 day'));


        \Log::info('Auctions: ' . $auctions->count());

        foreach ($auctions as $auction) {

            try {
                $freshData = ParserFactory::getParser($auction->auction_link)->get();

            } catch (\Throwable $ex) {
                echo 'Не удалось спарсить аукцион #' . $auction->id . "\n";

                \Log::info('Не удалось спарсить аукцион #' . $auction->id . "\nError: " . $ex->getMessage());

                continue;
            }

            \Log::info('step 4');

            // Проверка аукциона на статусы
            if (($auction->auction_status_id !== $freshData['auction']['auctionStatus'])) {
                $currentKey = '';
                $currentFormattedString = $currentString = '<li>' . $auction->auction_link . '</il>' . "\n";

                switch ($freshData['auction']['auctionStatus']) {
                    case 5:
                    case 7:
                        echo 'Внимание! Закупка #' . $auction->auction_number . ' отменена.' . "\n";
                        $currentKey = 'cancelled';
                        break;

                    case 4:
                    case 6:
                        echo 'Закупка #' . $auction->auction_number . ' завершена.' . "\n";
                        $currentFormattedString = "Аукцион: " . $auction->auction_link . "<br>"
                            . "Победитель: " . $freshData['auction']['winner']
                            . "<br>" . "Цена: " . $freshData['auction']['winnerPrice'];
                        $currentKey = 'completed';

                        break;
                    case 3:
                        echo 'Для закупки #' . $auction->auction_number . ' идет работа комиссии.' . "\n";
                        break;
                }

                $mailChanges[$currentKey][] =
                    [
                        'auction' => $auction,
                        'data' => [
                            'formattedString' => $currentFormattedString,
                        ],
                        'updatedFields' => [
                            'auction_status_id' => $freshData['auction']['auctionStatus'],
                            'auction_winner' => $freshData['auction']['winner'],
                            'winner_price' => $freshData['auction']['winnerPrice'],


                        ]
                    ];

                unset($currentKey);
            }

            \Log::info('step 5');

            // Проверка аукциона на даты

            try {
                $deadlineDbTs = strtotime($auction->applicationdeadline);
                $deadlineDb = date("d.m.Y", $deadlineDbTs);
            } catch (\Throwable $ex) {
                $deadlineDb = 0;

                \Log::info('step 6, error: ' . $ex->getMessage());
            }

            try {
                $deadlineNewTs = strtotime($freshData['auction']['deadline']);
                $deadlineNew = date("d.m.Y", $deadlineNewTs);
            } catch (\Throwable $ex) {
                $deadlineNew = 0;

                \Log::info('step 7, error: ' . $ex->getMessage());
            }

            try {
                $dateDbTs = strtotime($auction->auctiondate);
                $dateDb = date("d.m.Y", $dateDbTs);
            } catch (\Throwable $ex) {
                $dateDb = 0;

                \Log::info('step 8, error: ' . $ex->getMessage());
            }

            try {
                $dateNewTs = strtotime($freshData['auction']['auctionDate']);
                $dateNew = date("d.m.Y", $dateNewTs);
            } catch (\Throwable $ex) {
                $dateNew = 0;
            }


            if (($deadlineDb !== $deadlineNew) || ($dateDb !== $dateNew)) {
                echo 'Дата окончания подачи заявок для закупки #' . $auction->auction_number . ' изменена.' . "\n" . 'Старая дата — ' . $deadlineDb . "\n" . 'Новая дата - ' . $deadlineNew . "\n";
                echo 'Дата проведения торгов закупки #' . $auction->auction_number . ' изменена.' . "\n" . 'Старая дата — ' . $dateDb . "\n" . 'Новая дата - ' . $dateNew . "\n";

                $currentObject = [
                    'auction' => $auction,
                    'data' => [
                        'formattedString' => "Аукцион: " . $auction->auction_link . "<br>"
                            . "Старая дата подачи: " . $deadlineDb . ". Новая дата подачи: "
                            . $deadlineNew . "<br>" . "Старая дата торгов: " . $dateDb . "
                            . Новая дата торгов: " . $dateNew,
                    ],
                    'updatedFields' => []
                ];

                if ($deadlineDb !== $deadlineNew) {
                    $currentObject['updatedFields']['applicationdeadline'] = $deadlineNew;
                }

                if ($dateDb !== $dateNew) {
                    $currentObject['updatedFields']['auctiondate'] = $dateNew;
                }

                $mailChanges['delayed'][] = $currentObject;
                unset($currentObject);
            }


            if ($tomorrowDate == $dateDb) {
                echo 'Завтра — ' . $tomorrowDate . ' — проводится закупка #' . $auction->auction_number . "\n";
                $mailChanges['tomorrow'][] =
                    [
                        'auction' => $auction,
                        'data' => [
                            'formattedString' => "Аукцион: " . $auction->auction_link . " играется: " . $dateDb,
                        ]
                    ];
            }

        }

        $mailBodies = [
            // ключ - ID пользователя, значение - части письма для этого пользователя
        ];

        \Log::info('step 9, data: ' . json_encode($mailChanges));


        $adminMailBody = '';

        foreach (array_keys($mailChanges) as $k) {
            if (empty($mailChanges[$k])) {
                continue;
            }

            switch ($k) {
                case 'delayed':
                    $currentTitle = '<h3>Перенесены следующие аукционы:</h3><ul>';
                    break;

                case 'cancelled':
                    $currentTitle = '<h3>Отменены следующие аукционы:</h3><ul>';
                    break;

                case 'completed':
                    $currentTitle = '<h3>Завершены следующие аукционы:</h3><ul>';
                    break;

                case 'tomorrow':
                    $currentTitle = '<h3>Tomorrow следующие аукционы:</h3><ul>';
                    break;

                default:
                    $currentTitle = '<h3>Информация по следующим аукционам:</h3><ul>';
            }

            $adminMailBody .= $currentTitle;

            $t = 0;
            foreach ($mailChanges[$k] as $auctionObject) {
                $currentString = '<li>' . $auctionObject['data']['formattedString'] . '</il>' . "\n";

                // для админа
                $adminMailBody .= $currentString;

                // если для этого пользователя ещё не было ни одной части письма, то инициализируем
                if (!isset($mailBodies[$auction->user_id])) {
                    $mailBodies[$auctionObject['auction']->user_id] = '';
                }

                if ($t === 0) {
                    $mailBodies[$auctionObject['auction']->user_id] .= $currentTitle;
                }

                $mailBodies[$auctionObject['auction']->user_id] .= $currentString;

                $t++;
            }

            $adminMailBody .= '</ul>';
        }

        if(empty($adminMailBody)){
            echo 'Нечего отправлять, выходим' . "\n";
            return 1;
        }

        // тела писем админу и пользователям построены, можно начинать

        $emailParams = [
            'from' => 'snayp.ru <admin@mail.snayp.ru>',
            'to' => 'Kalinin Rostislav <isatcod@gmail.com>',
            'subject' => 'Информация по аукционам за ' . date('d.m.Y'),
            'html' => $adminMailBody,
        ];

        # Make the call to the client.
        $result = $mgClient->messages()->send($domain, $emailParams);

        \Log::info('Mailgun message created, cancelled: ' . (string)$result->getId());

        if (empty($result)) {
            echo 'Не удалось отправить!' . $adminMailBody . "\n";
            Log::error('Не удалось отправить письмо следующего содержания' . "\n" . $adminMailBody);
        }

        foreach ($mailBodies as $userId => $mailBody) {

            if (empty($users[$userId])) {
                \Log::warning('Не удалось найти пользователя с ID # ' . $userId);
                continue;
            }

            if(empty($mailBody)){
                \Log::warning('Не удалось найти тело письма');
                continue;
            }

            $emailParams = [
                'from' => 'snayp.ru <admin@mail.snayp.ru>',
                'subject' => 'Информация по аукционам за ' . date('d.m.Y'),
//                'to' => '<isatcod+user@gmail.com>',
                'to' => '<' . $users[$userId]->email . '>',
                'html' => $mailBody,
            ];

            # Make the call to the client.
            $result = $mgClient->messages()->send($domain, $emailParams);

            \Log::info('Mailgun message created, ' . $k . ': ' . (string)$result->getId());

            if (empty($result)) {
                echo 'Не удалось отправить!' . $mailBody . "\n";
                Log::error('Не удалось отправить письмо следующего содержания' . "\n" . $mailBody);
                continue;
            }

        }

        // всё хорошо, выполняем дела после отправки письма
        foreach (array_keys($mailChanges) as $k) {
            foreach ($mailChanges[$k] as $auctionObject) {
                $isAuctionUpdated = false;

                if (empty($auctionObject['updatedFields'])) {
                    continue;
                }

                foreach ($auctionObject['updatedFields'] as $key => $value) {
                    $auctionObject['auction']->{$key} = $value;
                    echo 'Обновили ' . $key . ' на ' . $value . ' для аукциона ' . $auctionObject['auction']->auction_number . "\n";
                    $isAuctionUpdated = true;
                }


                if ($isAuctionUpdated) {
                    $auctionObject['auction']->save();
                }
            }
        }

    }
}

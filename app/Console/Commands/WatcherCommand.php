<?php

namespace App\Console\Commands;

use App\Auction;
use App\Classes\BaseParser;
use App\Classes\ParserFactory;
use App\Classes\StatusParser;
use App\Classes\Ea44Parser;
use GuzzleHttp\Client;
use http\Exception\BadHeaderException;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;
use Psy\Exception\RuntimeException;
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

        \Log::info('step 3');

        $mailChanges = [
            'cancelled' => [],
            'completed' => [],
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
                switch ($freshData['auction']['auctionStatus']) {
                    case 5:
                    case 7:
                        echo 'Внимание! Закупка #' . $auction->auction_number . ' отменена.' . "\n";
                        $mailChanges['cancelled'][] = $auction;
                        break;
                    case 4:
                    case 6:
                        echo 'Закупка #' . $auction->auction_number . ' завершена.' . "\n";
                        $mailChanges['completed'][] = "Аукцион: " . $auction->auction_link . "<br>" . "Победитель: " . $freshData['auction']['winner'] . "<br>" . "Цена: " . $freshData['auction']['winnerPrice'];
                        break;
                    case 3:
                        echo 'Для закупки #' . $auction->auction_number . ' идет работа комиссии.' . "\n";
                        break;
                }

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
                $mailChanges['delayed'][] = "Аукцион: " . $auction->auction_link . "<br>" . "Старая дата подачи: " . $deadlineDb . ". Новая дата подачи: " . $deadlineNew . "<br>" . "Старая дата торгов: " . $dateDb . ". Новая дата торгов: " . $dateNew;
            }



            if ($tomorrowDate == $dateDb) {
                echo 'Завтра — ' .$tomorrowDate . ' — проводится закупка #' . $auction->auction_number . "\n";
                $mailChanges['tomorrow'][] = "Аукцион: " . $auction->auction_link  . " играется: " . $dateDb;
            }



        }

        $mailBodies = [
            // ключ - ID пользователя, значение - части письма для этого пользователя
        ];

        \Log::info('step 9, data: ' . json_encode($mailChanges));

        if (!empty($mailChanges['cancelled'])) {
            $currentTitle =  '<h3>Отменены следующие аукционы:</h3><ul>';
            $adminMailBody = $currentTitle;

            $users = User::whereIn('id', array_keys($mailBodies))->get()->keyBy('id');
            foreach($mailBodies as $userId => $userMailParts){
            }

            foreach ($mailChanges['cancelled'] as $auction) {
                $currentString = '<li>' . $auction->auction_link . '</il>' . "\n";

                // для админа
                $adminMailBody .= $currentString;

                // если для этого пользователя ещё не было ни одной части письма, то инициализируем
                if(empty($mailBodies[$auction->user_id])){
                    $mailBodies[$auction->user_id] = [];
                }

                // если для этого пользователя ещё не было ни одного отменённого ауукциона, то вставляем заголовок об отменённых аукционах
                if(empty($mailBodies[$auction->user_id]['cancelled'])){
                    $mailBodies[$auction->user_id]['cancelled'] = $currentTitle;
                }

                $mailBodies[$auction->user_id]['cancelled'] .= $currentString;
            }

            $adminMailBody .= '</ul>';

            $emailParams = [
                'from' => 'snayp.ru <admin@mail.snayp.ru>',
                'to' => 'Kalinin Rostislav <isatcod@gmail.com>',
                'subject' => 'Отмененные аукционы',
                'html' => $adminMailBody,
            ];

//            dd($mailBodies);

            # Make the call to the client.
            $result = $mgClient->messages()->send($domain, $emailParams);

            \Log::info('Mailgun message created, cancelled: ' . (string)$result->getId());

            if (empty($result)) {
                echo 'Не удалось отправить!' . $adminMailBody . "\n";
                Log::error('Не удалось отправить письмо следующего содержания' . "\n" . $adminMailBody);
            }

            unset($adminMailBody);
        }

        if (!empty($mailChanges['completed'])) {
            $adminMailBody = '<h3>Завершены следующие аукционы:</h3><ul>';

            foreach ($mailChanges['completed'] as $completedAuction) {
                $adminMailBody .= '<li>' . $completedAuction . '</il>' . "\n";
            }

            $adminMailBody .= '</ul>';

            $emailParams = [
                'from' => 'snayp.ru <admin@mail.snayp.ru>',
                'to' => 'Kalinin Rostislav <isatcod@gmail.com>',
                'subject' => 'Завершенные аукционы',
                'html' => $adminMailBody,
            ];

            # Make the call to the client.
            $result = $mgClient->messages()->send($domain, $emailParams);

            \Log::info('Mailgun message created, completed: ' . (string)$result->getId());

            if (empty($result)) {
                echo 'Не удалось отправить!' . $adminMailBody . "\n";
                Log::error('Не удалось отправить письмо следующего содержания' . "\n" . $adminMailBody);
            }

            unset($adminMailBody);
        }

        if (!empty($mailChanges['delayed'])) {
            $adminMailBody = '<h3>Перенесены следующие аукционы:</h3><ul>';

            foreach ($mailChanges['delayed'] as $delayedAuction) {
                $adminMailBody .= '<li>' . $delayedAuction . '</il>' . "\n";
            }

            $adminMailBody .= '</ul>';

            $emailParams = [
                'from' => 'snayp.ru <admin@mail.snayp.ru>',
                'to' => 'Kalinin Rostislav <isatcod@gmail.com>',
                'subject' => 'Перенесенные аукционы',
                'html' => $adminMailBody,
            ];

            # Make the call to the client.
            $result = $mgClient->messages()->send($domain, $emailParams);

            \Log::info('Mailgun message created: ' . (string)$result->getId());

            if (empty($result)) {
                echo 'Не удалось отправить!' . $adminMailBody . "\n";
                Log::error('Не удалось отправить письмо следующего содержания' . "\n" . $adminMailBody);
            }

            unset($adminMailBody);
        }


        if (!empty($mailChanges['tomorrow'])) {
            $adminMailBody = '<h3>Завтра играются следующие аукционы:</h3><ul>';

            foreach ($mailChanges['tomorrow'] as $tomorrowAuction) {
                $adminMailBody .= '<li>' . $tomorrowAuction . '</il>' . "\n";
            }

            $adminMailBody .= '</ul>';

            $emailParams = [
                'from' => 'snayp.ru <admin@mail.snayp.ru>',
                'to' => 'Kalinin Rostislav <isatcod@gmail.com>',
                'subject' => 'Аукционы на завтра',
                'html' => $adminMailBody,
            ];

            # Make the call to the client.
            $result = $mgClient->messages()->send($domain, $emailParams);

            \Log::info('Mailgun message created: ' . (string)$result->getId());

            if (empty($result)) {
                echo 'Не удалось отправить!' . $adminMailBody . "\n";
                Log::error('Не удалось отправить письмо следующего содержания' . "\n" . $adminMailBody);
            }

            unset($adminMailBody);
        }


    }
}

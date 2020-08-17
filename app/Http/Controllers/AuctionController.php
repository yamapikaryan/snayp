<?php

namespace App\Http\Controllers;

use App\Auction;
use App\Classes\BaseParser;
use App\Classes\ParserFactory;
use App\File;
use App\Player;
use App\Etp;
use App\Client;
use App\AuctionStatus;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mail;
use Mailgun\Mailgun;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auctions = Auction::with(['client'])->orderBy('created_at', 'DESC')->get();

        if ($request->ajax()) {
            return [
                'auctions' => $auctions,
            ];
        }

        return view('auction.index', [
            'auctions' => $auctions
        ]);
    }

    public function mylist()
    {
        $auctions = Auction::with(['client'])->where('user_id', Auth::id())->get();
        return view('auction.index')->with('auctions', $auctions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $auction = new Auction();
        $players = Player::all();
        $selectedPlayerID = 1;
        $user = Auth::user();
        $etps = Etp::all();


        return view('auction.create')->with([
            'auction' => $auction,
            'players' => $players,
            'selectedPlayerID' => $selectedPlayerID,
            'etps' => $etps,
            'user' => $user,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // @todo убрать на релизе!
        if ($_SERVER['REMOTE_ADDR'] !== env('DEV_IP') && Auction::where('auction_number', $request->auction_number)->count()) {
            throw new \RuntimeException('Заявка по этому аукциону уже создана ранее');
        }

//        if (!is_numeric($request->input('maxprice'))) {
//            $request->request->add(['maxprice' => '0']);
//        }

        if ($request->input('client_name') == null) {
            $request->request->add(['client_name' => 'Заказчик неизвестен']);
        }

        if ($request->input('auctionStatus') == null) {
            $request->request->add(['auctionStatus' => 1]);
        }

        $client = Client::firstOrCreate(['name' => $request->input('client_name')]);
        $request->request->add(['client_id' => $client->id]);

        /** @var Auction $auction */
        $auction = Auction::create($request->all());

        // добавляем документы к аукциону, если они есть
        $files = $request->file('files');

        if (!empty($files)) {
            $zip = new \ZipArchive();

            $tmpZipFilePath = storage_path('tmp/' . uniqid('zip_') . '.zip');

            // @todo очищать tmp периодически
            $zipIsOpened = $zip->open($tmpZipFilePath, \ZipArchive::CREATE);

            if (!$zipIsOpened) {
                throw new \RuntimeException('Не удалось создать ZIP, попробуйте ещё раз');
            }

            foreach ($files as $file) {
                $currentPath = \Storage::putFileAs('', $file, uniqid('file_') . '.' . $file->getExtension());

                $localFile = new File();

                $localFile->object_id = $auction->id;
                $localFile->object_type = Auction::OBJECT_TYPE;
                $localFile->path = $currentPath;
                $localFile->name = $file->getClientOriginalName();
                $localFile->save();

                // storage_path($currentPath)
                $zip->addFile($file->getRealPath(), $localFile->name);
            }

            $zip->close();
        }


        # Instantiate the client.
        $mgClient = Mailgun::create(env('MAILGUN_API_KEY'), 'https://api.eu.mailgun.net/v3/mail.snayp.ru');
        $domain = env('MAILGUN_DOMAIN');

        $auctionUser = $auction->user;
        $emailParams = [
            'from' => 'snayp.ru <admin@mail.snayp.ru>',
            'to' => 'Kalinin Rostislav <isatcod@gmail.com>',
            'subject' => 'Новая заявка',
            'html' => '<h1>Добавлена заявка.</h1><ul>
                   <li>Пользователь: ' . (!empty($auctionUser) ? $auctionUser->name : 'НЕИЗВЕСТНО !') . '</li>
                   <li>Игрок: ' . $auction->player->name . '</li>
                   <li>Номер в ЕИС: <a href=' . $auction->auction_link . '>' . $auction->auction_number . '</a> </li>
                   <li>Площадка: ' . $auction->etp->name . '</li>
                   <li>Наш предел цены: ' . $auction->ourprice . '</li>
                   <li>Дата окончания подачи заявок: ' . $auction->applicationdeadline . '</li>
                   <li>Комментарий: ' . $auction->comment . '</li></ul> ',
        ];
        if (!empty($files) && is_readable($tmpZipFilePath)) {
            $emailParams['attachment'] = [
                ['filePath' => $tmpZipFilePath, 'filename' => $auction->auction_number . '.zip']
            ];
        }

        # Make the call to the client.
        $result = $mgClient->messages()->send($domain, $emailParams);

        return ['status' => 1, 'url' => route('auctions.index')];
    }

    public function sendList(Request $request)
    {
        $params = $request->all();

        $mailGun = Mailgun::create(env('MAILGUN_API_KEY'), 'https://api.eu.mailgun.net/v3/mail.snayp.ru');
        $domain = env('MAILGUN_DOMAIN');

        $managers = User::whereIn('id', array_keys($params['auctionsToSend']))->get()->keyBy('id');

        foreach ($params['auctionsToSend'] as $k => $v) {
            if (empty($managers[$k])) {
                continue;
            }

            $html = '<h3>Список аукционов:</h3>' . "\n" . '<ul>';
            foreach ($v['links'] as $link) {
                $html .= '<li><a href="' . $link . '">' . $link . '</a>'
                    . (!empty($params['linksData'][$link]['comment'])
                        ? (' — ' . $params['linksData'][$link]['comment']) : '') . '</li>' . "\n";
            }
            $html .= '</ul>';

            $emailParams = [
                'from' => 'snayp.ru <admin@mail.snayp.ru>',
                'to' => $managers[$k]->name . '<' . $managers[$k]->email . '>',
                'subject' => 'Новые аукционы',
                'html' => $html,
            ];


            # Make the call to the client.
            try {
                $result = $mailGun->messages()->send($domain, $emailParams);
            } catch (\Throwable $ex) {
                continue;
            }
        }

        return ['status' => 1, 'url' => route('next.index')];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Auction $auction)
    {
        $auctionUser = $auction->user;


        $files = $auction->files;
        return view('auction.show', compact('auction'))->with(['files' => $files]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $auction = Auction::find($id);
        $files = $auction->files;
        $players = Player::all();
        $etps = Etp::all();

        return view('auction.edit', compact('auction'))->with([
            'auction' => $auction,
            'files' => $files,
            'etps' => $etps,
            'players' => $players,
        ]);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        Auction::where('id', $id)->update($request->except(['_token', '_method']));
        $data = $request->all();

        $auction = Auction::where('id', $id)->first();


        $auction->auction_link = $data['auction_link'] ?? null;
        $auction->auction_number = $data['auction_number'] ?? null;
        $auction->etp_id = $data['etp_id'] ?? null;
        $auction->is_223fz = $data['is_223fz'] ?? null;
        $auction->auction_object = $data['auction_object'] ?? null;
        $auction->applicationdeadline = $data['applicationdeadline'] ?? null;
        $auction->auctiondate = $data['auctiondate'] ?? null;
        $auction->is_price_request = $data['is_price_request'] ?? null;
        $auction->maxprice = $data['maxprice'] ?? null;
        $auction->ourprice = $data['ourprice'] ?? null;
        $auction->player_id = $data['player_id'] ?? null;
        $auction->comment = $data['comment'] ?? null;

        // добавляем документы к аукциону, если они есть
        $files = $request->file('files');

        if (!empty($files)) {
            foreach ($files as $file) {
                $currentPath = \Storage::putFileAs('', $file, uniqid('file_') . '.' . $file->getExtension());

                $localFile = new File();

                $localFile->object_id = $auction->id;
                $localFile->object_type = Auction::OBJECT_TYPE;
                $localFile->path = $currentPath;
                $localFile->name = $file->getClientOriginalName();
                $localFile->save();
            }
        }

        if (!empty($data['deletedFileIds']) && is_array($data['deletedFileIds'])) {
//            File::where(['object_id' => $auction->id, 'object_type' => Auction::OBJECT_TYPE])
//                ->whereIn('id', $data['deletedFileIds'])
//                ->delete();
            $auction->files()->whereIn('id', $data['deletedFileIds'])->delete();
        }

        $auction->save();

        return redirect()->route('auctions.index')
            ->with('success', 'Данные закупки обновлены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function autoFill(Request $request)
    {
        $params = $request->request->all();

        $validator = \Validator::make($params, [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new \RuntimeException('Некорректный URL');
        }

        $baseParser = ParserFactory::getParser($params['url']);

        return $baseParser->get($params['url']);;
    }

    public function zipFiles(Request $request)

    {
        $params = $request->all();

        if (empty($params['auctionId']) || (int)$params['auctionId'] < 1) {
            throw new \RuntimeException('Некорректный запрос, пожалуйста, обновите страницу и попробуйте ещё раз');
        }

        $fileQueryBuilder = File::where(['object_id' => $params['auctionId'], 'object_type' => Auction::OBJECT_TYPE]);

        if (!empty($params['fileIds']) && is_array($params['fileIds'])) {
            $fileQueryBuilder->whereIn('id', $params['fileIds']);
        }

        $files = $fileQueryBuilder->get();

        if ($files->isEmpty()) {
            throw new \RuntimeException('Не найдено ни одного файла');
        }

        $zip = new \ZipArchive();

        $filePath = storage_path('tmp/' . uniqid('zip_') . '.zip');

        // @todo очищать tmp периодически
        $zipIsOpened = $zip->open($filePath, \ZipArchive::CREATE);

        if ($zipIsOpened !== true) {
            throw new \RuntimeException('Не удалось создать ZIP, попробуйте ещё раз');
        }

        foreach ($files as $file) {
            if (!is_readable($file->full_path)) {
                throw new \RuntimeException('Не удаётся прочитать файл #' . $file->id . ', попробуйте ещё раз');
            }

            $zip->addFile($file->full_path, !empty($file->name) ? $file->name : uniqid('file_'));
        }

//        $zip->addFromString('new.txt', 'text to be added to the new.txt file');

        $zip->close();

        if (!is_readable($filePath) || filesize($filePath) < 1) {
            throw new \RuntimeException('Не удалось создать ZIP, попробуйте ещё раз');
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        // заставляем браузер показать окно сохранения файла
        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=files.zip');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // читаем файл и отправляем его пользователю
        readfile($filePath);
        exit;
    }
}

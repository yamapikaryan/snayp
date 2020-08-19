@extends('layouts.app')

@section('content')


    <div class="card">
        <div class="card-header">
            Создать заявку
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('auctions.store') }}" id="main-form">
                @csrf
                <div class="form-group">
                    <label for="auction_link">Вставить ссылку на аукцион :</label>
                    <input type="text" class="form-control" name="auction_link" id="auction_link"
                           placeholder="Ссылка на zakupki.gov"/>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="btn_auto_fill">Автозаполнить форму ниже</button>
                </div>

                <div class="form-group">
                    <div class="card">
                        <div class="card-header">
                            Блок для автозаполнения после вставки ссылки на аукцион
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-group mb-2">
                                    <div class="row">
                                        <div class="col-sm">
                                            <label for="eis_number">Номер закупки в ЕИС:</label>
                                            <input type="text" class="form-control" name="auction_number"
                                                   id="auction_number" placeholder="0000000000000000000"/>
                                        </div>
                                        <div class="col-sm">
                                            <label for="etps">Эл. площадка:</label>
                                            <select class="form-control" name="etp_id" id="etp_id">
                                                @foreach ($etps as $etp)
                                                    <option value="{{ $etp->id }}">
                                                        {{ $etp->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm">
                                            <label for="check_fz">Номер ФЗ</label>
                                            <div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="is_223fz"
                                                           id="44fz" value="0">
                                                    <label class="form-check-label" for="is_223fz">
                                                        44-ФЗ
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="is_223fz"
                                                           id="223fz" value="1">
                                                    <label class="form-check-label" for="is_223fz">
                                                        223-ФЗ
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group mb-2">
                                    <div class="row">

                                        <div class="col-sm">
                                            <label for="auction_object">Объект закупки</label>
                                            <textarea class="form-control" name="auction_object" id="auction_object"
                                                      rows="2"
                                                      placeholder="Наименование объекта закупки"></textarea>
                                        </div>

                                        <div class="col-sm">
                                            <label for="client_name">Заказчик</label>
                                            <textarea class="form-control" name="client_name" id="client_name" rows="2"
                                                      placeholder="Название организации, осуществляющей размещение"></textarea>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group mb-2">
                                    <div class="row">

                                        <div class="col-sm">
                                            <label for="datepicker">Дата окончания подачи заявок:</label>
                                            <input type="text" name="applicationdeadline" id="application_deadline"
                                                   class="from-control" data-date-format="mm.dd.yyyy"
                                                   placeholder="__.__.____">
                                        </div>

                                        <div class="col-sm">
                                            <label for="datepicker">Дата проведения аукциона:</label>
                                            <input type="text" name="auctiondate" id="auction_date"
                                                   class="from-control"
                                                   data-date-format="mm.dd.yyyy" placeholder="__.__.____">
                                        </div>
                                        <div class="col-sm">
                                            <label for="check_fz">Тип закупки</label>
                                            <div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="is_price_request"
                                                           id="price_request_false" value="0">
                                                    <label class="form-check-label" for="is_price_request">
                                                        Аукцион
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="is_price_request"
                                                           id="price_request_true" value="1">
                                                    <label class="form-check-label" for="is_price_request">
                                                        Котировочный запрос
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col"></div>
                                    <label for="maxprice">Максимальная цена закупки, ₽</label>
                                    <input type="text" class="form-control" name="maxprice" id="maxprice"
                                           placeholder="000 0000,00 ₽"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="ourprice">Нижний предел нашей цены торгов, ₽</label>
                                <input type="text" class="form-control" name="ourprice" id="ourprice"
                                       placeholder="000 000,00"/>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="players">Выбрать участника аукциона:</label>
                                <select class="form-control" name="player_id" id="player_id">
                                    @foreach ($players as $player)
                                        <option
                                            value="{{ $player->id }}" {{ ( $player->id == $selectedPlayerID) ? 'selected' : '' }}>
                                            {{ $player->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="comment">Краткое описание закупки</label>
                                <textarea class="form-control" name="comment" id="comment" rows="2"
                                          placeholder="При необходимости добавить важный комментарий, конкретизировать объект закупки, место поставки и т.п."></textarea>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label for="uploadFiles">Прикрепить документы</label>
                                <div id="uploadFiles" class="dropzone">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">
                <input type="hidden" id="auction_status_id" name="auction_status_id" value="1">

                <button type="submit" class="btn btn-success">Создать заявку</button>
            </form>
        </div>
        <div class="waiting"><!-- Please, wait circle --></div>
    </div>

    <script>

        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });

        var dropZoneInstance;
        Dropzone.autoDiscover = false;
        $(document).ready(function () {

            $('#main-form').on('submit', function (e) {
                e.preventDefault();

                if ($(e.target).data('processing')) {
                    return false;
                }

                let formData = new FormData(e.target),
                    files = dropZoneInstance.files;

                if (files.length > 0) {
                    $.map(files, function (element, indx) {
                        formData.append('files[]', element);
                    });
                }

                // formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: $(e.target).attr('action'),
                    type: 'POST',
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success(response) {

                        if (typeof response.status !== 'undefined' && parseInt(response.status) === 1) {
                            alert('Заявка создана');
                            document.location.href = response.url;
                        } else {
                            alert('Что-то пошло не так');
                            console.error(response);
                            $(e.target).data('processing', false);
                        }
                    },
                    beforeSend() {
                        $(e.target).data('processing', 1);
                    },
                    complete() {
                        $(e.target).data('processing', false);
                    },
                    error(response) {
                        console.log(response);
                        alert(response.responseJSON.message);
                        $(e.target).data('processing', false);
                    }
                });
            });

            $('#btn_auto_fill').on('click', function () {
                if ($(this).data('processing')) {
                    return false;
                }

                let element = this, auctionLink = $('[name="auction_link"]').val();



                if (auctionLink.length < 10) {
                    alert('Пожалуйста, введите корректный URL');
                    return false;
                }

                if (auctionLink.includes("/view/documents.html") || auctionLink.includes("/view/event-journal.html")) {
                    const regex = /view\/(.+?)\./i;
                    let result = auctionLink.match(regex);
                    auctionLink = auctionLink.replace(result[1], "common-info");
                } else if (auctionLink.includes("/info/lot-list.html") || auctionLink.includes("/info/documents.html")) {
                    const regex = /info\/(.+?)\./i;
                    let result = auctionLink.match(regex);
                    auctionLink = auctionLink.replace(result[1], "common-info");
                }


                $.ajax({
                    url: '{{ route('auctions.autoFill') }}',
                    type: 'POST',
                    data: {
                        url: auctionLink,
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success(response) {

                        $('#auction_link').val(auctionLink);
                        $('#auction_number').val(response.auction.auctionNumber);
                        $('#etp_id').val(response.auction.etpId);
                        $('#client_name').val(response.auction.client);
                        $('#auction_object').val(response.auction.auctionObject);
                        $('#application_deadline').val(response.auction.deadline);
                        $('#auction_date').val(response.auction.auctionDate);
                        $('#maxprice').val(response.auction.maxPrice);
                        $('#auction_status_id').val(response.auction.auctionStatus);


                        if (response.auction.isPriceRequest == 1)
                            document.getElementById('price_request_true').checked = true;
                        else
                            document.getElementById('price_request_false').checked = true;

                        if (response.auction.is223fz == 1)
                            document.getElementById('223fz').checked = true;
                        else
                            document.getElementById('44fz').checked = true;


                    },
                    beforeSend() {
                        $(element).data('processing', 1);
                    },
                    complete() {
                        $(element).data('processing', false);
                    },
                    error(response) {
                        console.log(response);
                        alert(response.responseJSON.message);
                    }
                });
            });


            // Dropzone.options.myAwesomeDropzone = {
            //     init: function () {
            //         this.on("addedfile", function (file) {
            //         });
            //     }
            // };

            Dropzone.autoDiscover = false;
            dropZoneInstance = new Dropzone("#uploadFiles", {
                url: "{{ route('auctions.fileUpload') }}",
                autoProcessQueue: false,
                addRemoveLinks: true,
                dictRemoveFile: 'Удалить файл',
                dictDefaultMessage: 'Перетащите файлы сюда или кликните для выбора файлов'
            });


        });


    </script>

    <style>
        .dz-details, .dz-progress {
            display: none !important;
        }
    </style>
@endsection


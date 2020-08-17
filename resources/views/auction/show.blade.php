@extends('layouts.app')

@section('content')


    <div class="card">
        <div class="card-header">
            Карточка заявки
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm">
                    <a class="btn btn-primary" href="{{ route('auctions.index', $auction->id )}}">Назад</a>
                </div>
                    <div class="col-sm">
                    <a class="btn btn-primary" href="{{ route('auctions.edit', $auction->id) }}">Редактировать</a>
                </div>
                </div>
            </div>
            <div class="form-group">
                <div class="card">
                    <div class="card-header">
                        Основная информация
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-group mb-2">
                                <div class="row">

                                    <div class="col-sm">
                                        <div class="form-group">
                                            <div><label for="auction_link">Номер в ЕИС:</label></div>
                                            <div><a href="{{$auction->auction_link}}">{{$auction->auction_number}}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm">
                                        <div class="form-group">
                                            <div><label for="etp">Эл. площадка:</label></div>
                                            <div><a href="{{$auction->etp->link}}">{{$auction->etp->name}}</a></div>
                                        </div>
                                    </div>


                                    <div class="col-sm">
                                        <div class="form-group">
                                            <div><label for="ourprice">Нижний предел нашей цены закупки, ₽:</label>
                                            </div>
                                            <div id="ourprice">{{$auction->ourprice}}</div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <div><label for="applicationdeadline">Дата окончания подачи заявок:</label>
                                            </div>
                                            <div id="applicationdeadline">{{$auction->applicationdeadline}}</div>
                                        </div>
                                    </div>

                                    <div class="col-sm">
                                        <div class="form-group">
                                            <div><label for="auctiondate">Дата проведения аукциона:</label></div>
                                            <div id="auctiondate">{{$auction->auctiondate}}</div>
                                        </div>
                                    </div>


                                    <div class="col-sm">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="files">Документы:</label>
                                                <div>
                                                    <table>
                                                        <tbody>
                                                        @foreach ($files as $file)
                                                            <tr>
                                                                <td><input type="checkbox" name="selected-files"
                                                                           value="{{ $file->id }}"/></td>
                                                                <td>
                                                                    <a href="{{Storage::url("$file->path")}}">{{$file->name}}</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div>
                                                    <form action="{{ route('auctions.zipFiles') }}" id="zip-form">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary" id="btn_zipfiles">
                                                            Скачать все файлы
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="card">
                            <div class="card-header">
                                Дополнительная информация
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-group mb-2">

                                        <div class="row">

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <div><label for="auction_object">Объект закупки:</label></div>
                                                    <div id="auction_object">{{$auction->auction_object}}</div>
                                                </div>
                                            </div>


                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <div><label for="maxprice">Максимальная цена закупки, ₽:</label>
                                                    </div>
                                                    <div id="maxprice">{{$auction->maxprice}}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <div><label for="client_name">Заказчик:</label></div>
                                                    <div id="client_name">{{$auction->client->name}}</div>
                                                </div>
                                            </div>


                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <div><label for="player">Участник аукциона:</label></div>
                                                    <div id="player">
                                                        {{$auction->player->name}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">

                                        <div class="col-sm">
                                            <div class="form-group">
                                                <div><label for="comment">Краткое описание закупки:</label></div>
                                                <div>
                                                    {{$auction->comment}}
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="is_price_request">Тип закупки и номер ФЗ:</label>
                                                <div id="is_price_request">

                                                @if ($auction->is_price_request == 1)
                                                        Котировочный запрос
                                                    @else
                                                        Аукцион
                                                    @endif

                                                </div>
                                                <div id="fz">
                                                    @if ($auction->is_223fz == 1)
                                                        223-ФЗ
                                                    @else
                                                        44-ФЗ
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>


        <script>
            $(document).ready(function () {

                $('[name="selected-files"]').on('change', function () {
                    $('#btn_zipfiles').text($('[name="selected-files"]:checked').length ? 'Скачать выбранные файлы' : 'Скачать все файлы');
                });

                $('#zip-form').on('submit', function (e) {
                    e.preventDefault();

                    if ($(e.target).data('processing')) {
                        return false;
                    }

                    let requestData = {
                        auctionId: '{{ $auction->id }}'
                    };

                    let checkedFiles = $('[name="selected-files"]:checked');

                    if (checkedFiles.length) {
                        checkedFiles.each((index, checkedFile) => {
                            if (typeof requestData.fileIds === 'undefined') {
                                requestData.fileIds = [];
                            }

                            requestData.fileIds.push($(checkedFile).attr('value'));
                        });
                    }

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        beforeSend() {
                            $(e.target).data('processing', true);
                        },
                        type: 'GET',
                        url: '{{ route('auctions.zipFiles') }}',
                        data: requestData,
                        cache: false,
                        contentType: false,
                        xhrFields: {
                            responseType: 'blob'
                        },
                        complete() {
                            $(e.target).data('processing', false);
                        },
                        success: function (data, textStatus, jqXHR) {
                            var blob = new Blob([data], {type: 'application/zip'});
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = '{{ $auction->auction_number }}.zip';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        },
                        error: function () {
                            $(e.target).data('processing', false);
                            alert('Ошибка, пожалуйста, попробуйте ещё раз или обновите страницу');
                        }
                    });

                });
            });
        </script>

@endsection


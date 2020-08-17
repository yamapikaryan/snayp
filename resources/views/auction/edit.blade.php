@extends('layouts.app')

@section('content')


    <div class="card">
        <div class="card-header">
            Редактирование заявки
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('auctions.show', $auction->id) }}">Назад</a>
                </div>
            </div>
            <div class="form-group">
                <form method="POST" action="{{ route('auctions.update', $auction->id) }}" id="main-form">
                    @csrf
                    <div class="form-group">
                        <label for="auction_link">Ссылка на закупку:</label>
                        <input type="text" class="form-control" name="auction_link" id="auction_link"
                               value="{{$auction->auction_link}}"/>
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
                                                <div class="form-group">

                                                    <div class="form-group">
                                                        <div><label for="eis_number">Номер в ЕИС:</label></div>
                                                        <input type="text" class="form-control" name="auction_number"
                                                               id="auction_number"
                                                               value="{{$auction->auction_number}}"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm">
                                                <label for="etps">Эл. площадка:</label>
                                                <select class="form-control" name="etp_id" id="etp_id">
                                                    @foreach ($etps as $etp)
                                                        @if ($etp->id == $auction->etp->id)
                                                            <option selected
                                                                    value="{{ $etp->id }}">{{ $etp->name }}</option>
                                                        @else
                                                            <option value="{{ $etp->id }}">{{ $etp->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <label for="check_fz">Номер ФЗ</label>
                                                <div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_223fz"
                                                               id="44fz"
                                                               value="0" {{$auction->is_223fz == 0 ? 'checked="checked"': ''}}>
                                                        <label class="form-check-label" for="is_223fz">
                                                            44-ФЗ
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_223fz"
                                                               id="223fz"
                                                               value="1" {{$auction->is_223fz == 1 ? 'checked="checked"': ''}}>
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
                                                >{{$auction->auction_object}}</textarea>
                                            </div>

                                            {{--                                            <div class="col-sm">
                                                                                            <label for="client_name">Заказчик</label>
                                                                                            <textarea class="form-control" name="client_name" id="client_name"
                                                                                                      rows="2"
                                                                                                      >{{$auction->client->name}}</textarea>
                                                                                        </div>--}}

                                        </div>
                                    </div>

                                    <div class="form-group mb-2">
                                        <div class="row">

                                            <div class="col-sm">
                                                <label for="datepicker">Дата окончания подачи заявок:</label>
                                                <input type="text" name="applicationdeadline" id="application_deadline"
                                                       class="from-control" data-date-format="mm.dd.yyyy"
                                                       value="{{$auction->applicationdeadline}}">
                                            </div>

                                            <div class="col-sm">
                                                <label for="datepicker">Дата проведения аукциона:</label>
                                                <input type="text" name="auctiondate" id="auction_date"
                                                       class="from-control"
                                                       data-date-format="mm.dd.yyyy" value="{{$auction->auctiondate}}">
                                            </div>
                                            <div class="col-sm">
                                                <label for="check_fz">Тип закупки</label>
                                                <div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="is_price_request"
                                                               id="price_request_false"
                                                               value="0" {{$auction->is_price_request == 0 ? 'checked="checked"': ''}}>
                                                        <label class="form-check-label" for="is_price_request">
                                                            Аукцион
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="is_price_request"
                                                               id="price_request_true"
                                                               value="1" {{$auction->is_price_request == 1 ? 'checked="checked"': ''}}>
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
                                               value="{{$auction->maxprice}}"/>
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
                                           value="{{$auction->ourprice}}"/>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="players">Выбрать участника аукциона:</label>
                                    <select class="form-control" name="player_id" id="player_id">
                                        @foreach ($players as $player)
                                            @if ($player->id == $auction->player->id)
                                                <option selected
                                                        value="{{ $player->id }}">{{ $player->name }}</option>
                                            @else
                                                <option value="{{ $player->id }}">{{ $player->name }}</option>
                                            @endif
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
                                    >{{$auction->comment}}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>

                <div class="row">
                    <div class="col-md-12">
                        <div id="local-app">
                            <file-list :prop-files='<?= json_encode($files->pluck('name', 'id')->toArray()); ?>'></file-list>
                        </div>
                    </div>
                </div>

                <button class="btn btn-success" id="btn-submit">Сохранить изменения</button>

            </div>
        </div>
    </div>


    <script type="text/x-template" id="file-list">
        <div>
            <template v-if="filesData.length >0">

                <p v-if="!isLoading"
                   style="padding: 13px;" class="bg-info">Выбрано файлов:
                    <b v-text="filesData.length"></b>
                </p>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Имя файла</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(fileData, index) in filesData" :key="fileData.name + fileData.size">

                        <td v-text="fileData.name"></td>

                        <td>
                            <span aria-hidden="true" @click="hideFile(fileData)" v-if="!isLoading"
                                  title="Remove file" style="cursor: pointer;">×</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </template>

            <label :for="'form-file' + fileInputQuantity" id="button" v-if="!isLoading"
                   class="btn btn-primary btn-sm"
                   v-text="parseInt(filesData.length) === 0 ? 'Выберите файлы' : 'Добавить ещё файлов'">
            </label>

            <input type="file" :id="'form-file' + file" multiple
                   v-for="file in fileInputQuantity"
                   style="display: none;" @change="setFiles($event)"
            />
        </div>
    </script>

    <script>
        $(document).ready(() => {
            var fileListWrapper = Vue.component('file-list', {
                template: $('#file-list').html(),

                props: {
                    propFiles:{
                        type: Object
                    }
                },

                data() {
                    return {
                        filesData: [],
                        fileInputQuantity: 1,
                        isLoading: false,
                        maxFileSizeMb: 30,
                        deletedFileIds: []
                    }
                },

                computed: {
                    files: function () {
                        let self = this, files = [], j = 0;

                        for (var i = 1; i <= self.fileInputQuantity; i++) {
                            if ($('#form-file' + i).length) {
                                var currentFileList = $('#form-file' + i)[0].files;
                                if (typeof currentFileList.length !== 'undefined' && currentFileList.length > 0) {
                                    $.each(currentFileList, function (index, file) {
                                        files.push(file);
                                        j++;
                                    });
                                }
                            }
                        }

                        return files;
                    }
                },

                mounted() {
                    let self = this;

                    $('#btn-submit').on('click', () => {
                        self.update();
                    });

                    // загружаем в компонент ранее добавленные файлы
                    if (typeof self.propFiles !== 'undefined' && Object.keys(self.propFiles).length > 0){
                        let i = 0;
                        $.each(self.propFiles, function(fileId, fileName) {

                            self.filesData.push({
                                name: fileName,
                                originalName: fileName,
                                size: 0,
                                sortOrder: i,
                                id: fileId,
                            });
                            i++;
                        });

                        self.fileInputQuantity = i;
                    }
                },

                methods: {
                    update() {
                        let self = this;

                        let form = document.querySelector('#main-form');
                        let formData = new FormData(form);

                        // add chosen files
                        if (self.filesData.length > 0) {
                            let i = 0;
                            $.each(self.filesData, function (index, fileData) {
                                var currentFile = self.findFile(fileData);

                                if (currentFile == null) {
                                    return;
                                }

                                // append uploaded file
                                formData.append('files[' + i + ']', currentFile);
                                i++;
                            });
                        }

                        if(self.deletedFileIds.length > 0) {
                            let i = 0;
                            self.deletedFileIds.map(deletedFileId => {
                                formData.append('deletedFileIds[' + i + ']', deletedFileId);
                                i++;
                            });
                        }

                        return $.ajax({
                            url: document.location.href.replace('/edit', '/update'),
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,

                            beforeSend: function () {
                                self.isLoading = true;
                            },
                            complete: function () {
                                self.isLoading = false;
                            },
                            error: function () {
                                alert('Something went wrong, please reload this page');
                                self.isLoading = false;
                            },
                            success: function (data) {
                                data.status = typeof data !== 'object' || typeof data.error !== 'undefined' ? 0 : 1;

                                switch (data.status) {
                                    case 0:
                                        alert(typeof data.error === 'undefined'
                                            ? 'Something went wrong, please reload this page' : data.error);
                                        break;

                                    case 1:
                                        document.location.reload();
                                        break;
                                }
                            },
                        });
                    },

                    findFile(fileData) {
                        var result = null;
                        $.each(this.files, function (index, file) {
                            if (result === null && file.name === fileData.originalName && file.size === fileData.size) {
                                result = file;
                            }
                        });

                        return result;
                    },

                    filesSortUpdate: function (event) {
                        this.filesData.splice(event.newIndex, 0, this.filesData.splice(event.oldIndex, 1)[0]);
                    },

                    hideFile: function (fileData) {
                        var self = this, buffer = [];

                        if (!confirm(parseInt(fileData.id) > 0
                            ? 'Вы действительно хотите удалить ранее загруженный файл? ' +
                            'Файл будет удалён при нажатии на кнопку "Сохранить изменения"'
                            : 'Исключить этот файл из загрузки?')) {
                            return false;
                        }

                        if (parseInt(fileData.id) > 0){
                           self.deletedFileIds.push(parseInt(fileData.id));
                        }

                        $.each(self.filesData, function (index, singleFileData) {
                            if (!(singleFileData.originalName === fileData.originalName && fileData.size === singleFileData.size)) {
                                buffer.push(singleFileData);
                            }
                        });

                        Vue.set(self, 'filesData', buffer);
                    },

                    getReadableFileSizeString: function (fileSizeInBytes) {
                        var i = -1;
                        var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
                        do {
                            fileSizeInBytes = fileSizeInBytes / 1024;
                            i++;
                        } while (fileSizeInBytes > 1024);

                        return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
                    },

                    setFiles: function (event) {
                        var self = this;

                        if (typeof event.target.files !== 'undefined') {
                            $.each(event.target.files, function (index, file) {

                                // we need to use only unique original file, so let's check each file by its name and size
                                var isUniqueFile = true;
                                if (self.filesData.length > 0) {
                                    $.each(self.filesData, function (index, fileData) {
                                        if (typeof file.name !== 'undefined' && fileData.originalName === file.name
                                            && fileData.size === file.size
                                        ) {
                                            isUniqueFile = false;
                                            return true;
                                        }
                                    });
                                }

                                if (!isUniqueFile) {
                                    alert('Нельзя дважды добавить один и тот же файл "' + file.name + '"');
                                    return true;
                                }

                                if (parseInt(file.size) > (parseInt(self.maxFileSizeMb) * 1024 * 1024)) {
                                    alert('Файл "' + file.name + '" слишком большой, максимальный размер файла в MB: ' + self.maxFileSizeMb + ' MB');
                                    return true;
                                }

                                self.filesData.push({
                                    name: file.name,
                                    originalName: file.name,
                                    size: file.size,
                                    sortOrder: self.filesData.length,
                                    id: self.fileInputQuantity * -1,
                                });

                                self.fileInputQuantity++;
                            });
                        }
                    }
                }

            });

            const app = new Vue({
                el: '#local-app'
            });
        });

    </script>

@endsection


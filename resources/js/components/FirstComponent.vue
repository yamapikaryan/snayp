<template>
    <div id="app">

        <div v-if="status === 1">Пожалуйста, подождите</div>

        <template v-else>
            <b-modal id="modal-1" title="Выбрать менеджеров" ref="modal-1" @cancel="checkedManagers[selectedLink] = []"
                     ok-title="ОК" cancel-title="Отмена">

                <div class="row">
                    <template v-for="manager in managers">
                        <div class="col-md-6">
                            <input type="checkbox" :id="'manager' + manager.id" v-model="checkedManagers[selectedLink]"
                                   :value="manager.id">
                            <label :for="'manager' + manager.id" v-text="manager.name"></label>
                        </div>
                    </template>
                </div>


                <br>
                <div>
                    <span v-if="typeof loadedLinks[selectedLink] !== 'undefined' && typeof loadedLinks[selectedLink].ui !== 'undefined'">
                         <b-form-input v-model="loadedLinks[selectedLink].ui.comment" placeholder="Добавить комментарий"></b-form-input>
                    </span>
                </div>
            </b-modal>

            <div class="row">
                <div class="col-12 wb-ba">
                    <h3>Данные из внешнего источника</h3>

                    <b-button variant="info" @click="getLinks()">Загружаем с snayp.ru/external</b-button>
                    <!--            <input type="button" value="Загружаем с snayp.ru/external" @click="getLinks()"/>-->

                    <div class="accordion mt-3" id="accordion-links">

                        <div v-for="(link, index) in links">

                            <div class="card">
                                <div
                                    :class="{'card-header': true, 'bg-info': index % 2 === 0, 'bg-primary': index % 2 !== 0}"
                                    :id="'heading' + index" @click="parse(link)"
                                    data-toggle="collapse" :data-target="'#collapse' + index" aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            {{ link + ' ' }}
                                        </div>

                                        <div class="flex-grow-1 wb-bw" v-if="showNames(link).length > 0">
                                            <div><b>{{ showNames(link) }}</b></div>
                                        </div>
                                    </div>
                                </div>

                                <div :id="'collapse' + index" class="collapse" :aria-labelledby="'heading' + index"
                                     data-parent="#accordion-links">
                                    <div class="card-body">
                                        <template v-if="typeof loadedLinks[link] !== 'undefined'">

                                            <ul>
                                                <li>Предмет закупки: {{
                                                    decodeURI(loadedLinks[link]['auction']['auctionObject'])
                                                    }}
                                                </li>
                                                <li>Заказчик: {{ decodeURI(loadedLinks[link]['auction']['client']) }}
                                                </li>
                                                <li>Начальная цена:
                                                    {{ typeof loadedLinks[link]['auction']['maxPrice'] !== 'undefined' ? (decodeURI(loadedLinks[link]['auction']['maxPrice']) + ' руб.') : 'Не определена' }}
                                                </li>
                                                <li>Ссылка на аукцион: <a :href="link">{{decodeURI(loadedLinks[link]['auction']['auctionNumber'])}}</a>
                                                </li>
                                            </ul>
                                            <b-button @click="modalBox(link)">В очередь отправки</b-button>
                                        </template>
                                        <p v-else>
                                            Пожалуйста, подождите...
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div id="total" class="mt-3" v-if="Object.keys(managerList).length !== 0">
                        <h4>Итого</h4>
                        <!--            <input type="button" value="Сформировать список на отправку" @click="linksToManager(checkedManagers)"/>-->
                        <ul>
                            <li v-for="(currentLinkData, managerId) in managerList">
                                <p>{{ getManager(managerId).name }}</p>

                                <ol>
                                    <li v-for="link in currentLinkData.links">
                                        {{ link }} {{ typeof linksData[link] !== 'undefined' && typeof linksData[link]['comment'] !== 'undefined' ? linksData[link]['comment'] : '' }}
                                    </li>
                                </ol>
                            </li>
                        </ul>

                        <b-button @click="sendMails()">Отправить письма</b-button>
                    </div>

                </div>
            </div>

        </template>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                managers: [],

                // 1 - грузится, 2 - готов
                status: 1,

                inProgress: false,
                links: [],
                loadedLinks: {},
                checkedManagers: {},
                selectedLink: ''
            }
        },

        mounted() {
            let self = this;

            $.ajax({
                type: 'GET',
                data: {
                    type: 1,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success(response) {
                    self.$set(self, 'managers', response.managers);
                    self.status = 2;
                },
                beforeSend() {
                    self.inProgress = true;
                },
                complete() {
                    self.inProgress = false;
                },
                error(response) {
                    self.inProgress = false;

                    alert(_.get(response, 'responseJSON.message', 'Something went wrong'));
                }
            });
        },

        methods: {
            getManager(managerId) {
                return _.find(this.managers, manager => {
                    return parseInt(manager.id) === parseInt(managerId);
                });
            },


            modalBox(link) {
                this.selectedLink = link;
                // инициализация для конкретной link
                if (typeof this.checkedManagers[link] === 'undefined') {
                    this.$set(this.checkedManagers, link, []);
                }
                this.$refs['modal-1'].show();
            },


            getLinks: function () {
                let self = this;
                self.links = [];

                $.get('https://snayp.ru/external', (response) => {
                    let linksToSend = $(response);

                    linksToSend.find('#links li').each((currentItemIndex, currentItem) => {
                        self.links.push($(currentItem).text());
                    });
                });
            },

            sendMails() {
                let self = this;

                if (self.inProgress) {
                    return true;
                }

                $.ajax({
                    url: '/auctions/sendlist',
                    type: 'POST',
                    data: {
                        auctionsToSend: self.managerList,
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        linksData: self.linksData
                    },
                    success(response) {

                        if (typeof response.status !== 'undefined' && parseInt(response.status) === 1) {
                            alert('Письма отправлены');
                            document.location.href = response.url;
                        } else {
                            alert('Что-то пошло не так');
                            console.error(response);
                        }
                    },
                    beforeSend() {
                        self.inProgress = true;
                    },
                    complete() {
                        self.inProgress = false;
                    },
                    error(response) {
                        self.inProgress = false;
                        alert(response.responseJSON.message);
                    }
                });

            },


            parseResponse(link, response) {
                return true;
            },

            parse(link) {
                let self = this;

                if (self.inProgress) {
                    return true;
                }

                if (typeof self.loadedLinks[link] !== 'undefined') {
                    return self.parseResponse(link, self.loadedLinks[link]);
                }

                $.ajax({
                    url: '/auctions/autofill',
                    type: 'POST',
                    data: {
                        url: link,
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success(response) {
                        self.$set(self.loadedLinks, link, response);

                        self.$set(self.loadedLinks[link], 'ui', {
                            comment: ''
                        });

                        // self.loadedLinks[link] = response;
                        self.parseResponse(link, response);

                    },
                    beforeSend() {
                        self.inProgress = true;
                    },
                    complete() {
                        self.inProgress = false;
                    },
                    error(response) {
                        self.inProgress = false;
                        alert(response.responseJSON.message);
                    }
                });

            },

            showNames(link) {
                let self = this, result = [];

                _.each(this.checkedManagers[link], ManagerId => {
                    let currentManager = self.getManager(ManagerId);
                    result.push(currentManager.name);
                });

                return result.join(', ');
            },


        },

        computed: {
            managerList(){
                let self = this, result = {};

                _.each(this.checkedManagers, (checkedManager, link) => {
                    _.each(checkedManager, managerId => {
                        if (typeof result[managerId] === 'undefined') {
                            result[managerId] = {
                                links: [],
                            };
                        }

                        // если такая ссылка ещё не добавлена, добавить в "links"
                        if (result[managerId]['links'].indexOf(link) === -1) {
                            result[managerId]['links'].push(link);
                        }
                    });

                });

                return result;
            },

            linksData(){
                let self = this, result = {};

                _.each(self.loadedLinks,function(linkParams, link){
                    result[link] = linkParams.ui;
                });

                return result;
            }
        }

    }


</script>


<style>
    .wb-ba {
        word-break: break-all;
    }

    .wb-bw {
        word-break: break-word;
    }

</style>

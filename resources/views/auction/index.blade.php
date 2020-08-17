<?php

/** @var Auction $auction */

?>

@extends('layouts.app')

@section('content')


    <div class="card">
        <div class="card-header">
            <h3>Список активных закупок</h3>
        </div>

        {{--        <div class="form-group">--}}
        {{--            <div class="card">--}}
        {{--                <div class="card-header">--}}
        {{--                    Сортировка:--}}
        {{--                </div>--}}
        {{--                <div class="card-body">--}}
        {{--                    <div class="row">--}}
        {{--                        <div class="col-sm">--}}
        {{--                            <div class="form-group">--}}
        {{--                                <p>--}}
        {{--                                    <button class="btn btn-outline-primary my-2 my-sm-0 waves-effect waves-light"--}}
        {{--                                            onclick="sortByAdded()">По последним добавленным--}}
        {{--                                    </button>--}}
        {{--                                </p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="col-sm">--}}
        {{--                            <div class="form-group">--}}
        {{--                                <p>--}}
        {{--                                    <button class="btn btn-outline-primary my-2 my-sm-0 waves-effect waves-light"--}}
        {{--                                            onclick="sortByApplicationDeadline()">По дате подачи заявки--}}
        {{--                                    </button>--}}
        {{--                                </p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="col-sm">--}}
        {{--                            <div class="form-group">--}}
        {{--                                <p>--}}
        {{--                                    <button class="btn btn-outline-primary my-2 my-sm-0 waves-effect waves-light"--}}
        {{--                                            onclick="sortByAuctionDate()">По дате проведения торгов--}}
        {{--                                    </button>--}}
        {{--                                </p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="col-sm">--}}
        {{--                            <div class="form-group">--}}
        {{--                                <p>--}}
        {{--                                    <select id="user-select" onchange="sortByUser()" class='form-control'>--}}
        {{--                                        <option>Все менеджеры</option>--}}
        {{--                                        <option>Admin</option>--}}
        {{--                                        <option>Manager</option>--}}
        {{--                                    </select>--}}
        {{--                                </p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        <div class="card-body">
            <table class="table" id="auctionTable">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">ID</th>
                    <th scope="col">Менеджер</th>
                    <th scope="col">Номер аукциона</th>
                    <th scope="col">Комментарий</th>
                    <th scope="col">Подача до</th>
                    <th scope="col">Торги</th>
                    <th scope="col">Начальная цена, руб</th>
                    <th scope="col" class="hidden"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($auctions as $auction)
                    <tr data-raw="<?= base64_encode(json_encode($auction->toArray())); ?>">

                        <td></td>
                        <td>{{$auction->id}}</td>
                        <td>{{$auction->user->name}}</td>
                        <td><a href={{$auction->auction_link}}>{{$auction->auction_number}}</a></td>
                        <td>{{$auction->comment}}</td>

                        <?php

                        try {
                            $appDeadlineTs = strtotime($auction->applicationdeadline);
                        } catch (\Throwable $ex) {

                            $appDeadlineTs = 0;
                        }

                        ?>
                        <td data-order="<?= $appDeadlineTs ?>">{{$auction->applicationdeadline}}</td>


                        <?php

                        try {
                            $auctionDateTs = strtotime($auction->auctiondate);
                        } catch (\Throwable $ex) {
                            $auctionDateTs = 0;
                        }

                        ?>

                        <td data-order="<?= $auctionDateTs; ?>">{{$auction->auctiondate}}</td>
                        <td class="nowrap-element">{{$auction->maxprice}}</td>
                        <td>
                            <a href="{{route('auctions.show', $auction->id)}}">
                                <button type="button" class="btn btn-default float-left">
                                    <span style="font-size: 1.5rem; color: Dodgerblue;">
                                    <i class="far fa-eye"></i>
                                    </span>
                                </button>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>

        function convertDate(d) {
            var p = d.split(".");
            return +(p[2] + p[1] + p[0]);
        }

        // function sortByAdded() {
        //     var table, rows, switching, i, x, y, shouldSwitch;
        //     table = document.getElementById("auctionTable");
        //     switching = true;
        //     /*Make a loop that will continue until
        //     no switching has been done:*/
        //     while (switching) {
        //         //start by saying: no switching is done:
        //         switching = false;
        //         rows = table.rows;
        //         /*Loop through all table rows (except the
        //         first, which contains table headers):*/
        //         for (i = 1; i < (rows.length - 1); i++) {
        //             //start by saying there should be no switching:
        //             shouldSwitch = false;
        //             /*Get the two elements you want to compare,
        //             one from current row and one from the next:*/
        //             x = rows[i].getElementsByTagName("TD")[7];
        //             y = rows[i + 1].getElementsByTagName("TD")[7];
        //             //check if the two rows should switch place:
        //             if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
        //                 //if so, mark as a switch and break the loop:
        //                 shouldSwitch = true;
        //                 break;
        //             }
        //         }
        //         if (shouldSwitch) {
        //             /*If a switch has been marked, make the switch
        //             and mark that a switch has been done:*/
        //             rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        //             switching = true;
        //         }
        //     }
        // }
        //
        // function sortByApplicationDeadline() {
        //     var table, rows, switching, i, x, y, conx, cony, shouldSwitch;
        //     table = document.getElementById("auctionTable");
        //     switching = true;
        //     /*Make a loop that will continue until
        //     no switching has been done:*/
        //     while (switching) {
        //         //start by saying: no switching is done:
        //         switching = false;
        //         rows = table.rows;
        //         /*Loop through all table rows (except the
        //         first, which contains table headers):*/
        //         for (i = 1; i < (rows.length - 1); i++) {
        //             //start by saying there should be no switching:
        //             shouldSwitch = false;
        //             /*Get the two elements you want to compare,
        //             one from current row and one from the next:*/
        //             x = rows[i].getElementsByTagName("TD")[2];
        //             y = rows[i + 1].getElementsByTagName("TD")[2];
        //             conx = convertDate(x.innerHTML) || 0;
        //             cony = convertDate(y.innerHTML) || 0;
        //             //check if the two rows should switch place:
        //             if (conx < cony) {
        //                 //if so, mark as a switch and break the loop:
        //                 shouldSwitch = true;
        //                 break;
        //             }
        //         }
        //         if (shouldSwitch) {
        //             /*If a switch has been marked, make the switch
        //             and mark that a switch has been done:*/
        //             rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        //             switching = true;
        //         }
        //     }
        // }
        //
        // function sortByAuctionDate() {
        //     var table, rows, switching, i, x, y, conx, cony, shouldSwitch;
        //     table = document.getElementById("auctionTable");
        //     switching = true;
        //     /*Make a loop that will continue until
        //     no switching has been done:*/
        //     while (switching) {
        //         //start by saying: no switching is done:
        //         switching = false;
        //         rows = table.rows;
        //         /*Loop through all table rows (except the
        //         first, which contains table headers):*/
        //         for (i = 1; i < (rows.length - 1); i++) {
        //             //start by saying there should be no switching:
        //             shouldSwitch = false;
        //             /*Get the two elements you want to compare,
        //             one from current row and one from the next:*/
        //             x = rows[i].getElementsByTagName("TD")[4];
        //             y = rows[i + 1].getElementsByTagName("TD")[4];
        //             conx = convertDate(x.innerHTML) || 0;
        //             cony = convertDate(y.innerHTML) || 0;
        //             //check if the two rows should switch place:
        //             if (conx < cony) {
        //                 //if so, mark as a switch and break the loop:
        //                 shouldSwitch = true;
        //                 break;
        //             }
        //         }
        //         if (shouldSwitch) {
        //             /*If a switch has been marked, make the switch
        //             and mark that a switch has been done:*/
        //             rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        //             switching = true;
        //         }
        //     }
        // }
        //
        //
        // function sortByUser() {
        //     var input, filter, table, tr, td, i;
        //     input = document.getElementById("user-select");
        //     filter = input.value.toUpperCase();
        //     console.log(filter);
        //     table = document.getElementById("auctionTable");
        //     tr = table.getElementsByTagName("tr");
        //     for (i = 0; i < tr.length; i++) {
        //         td = tr[i].getElementsByTagName("td")[0];
        //         if (td) {
        //             if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        //                 tr[i].style.display = "";
        //             } else if (filter == "ВСЕ МЕНЕДЖЕРЫ") {
        //                 tr[i].style.display = "";
        //             } else {
        //                 tr[i].style.display = "none";
        //             }
        //         }
        //     }
        // }

        document.addEventListener("DOMContentLoaded", function() {
            // var table, deadline;
            // table = document.getElementById("auctionTable");
            // for (var i = 1, row; row = table.rows[i]; i++) {
            //     deadline = table.rows[i].getElementsByTagName("TD")[2].innerHTML;
            //     $.fn.dataTable.moment(deadline, "DD.MM.YYYY");
            //     console.log(deadline);
            // }


            var table = $('#auctionTable').DataTable({

                initComplete: function () {
                    this.api().column(2).each(function () {
                        var column = this;
                        var select = $('<select><option value=""selected>Все менеджеры</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                },


                iDisplayLength: 100,
                order: [[1, "desc"]],
                columnDefs: [
                    {
                        targets: [0],
                        class: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                ],

                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Russian.json'
                }
            });


            /* Formatting function for row details - modify as you need */
            function format(d) {
                // `d` is the original data object for the row
                return '<table cellpadding="10" cellspacing="0" border="0" style="padding-left:50px;">' +
                    '<tr>' +
                    '<td>Объект закупки:</td>' +
                    '<td>' + d.auction_object + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>Заказчик:</td>' +
                    '<td>' + d.client.name + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>Ссылка на аукцион:</td>' +
                    '<td> <a href="' + d.auction_link + '">' + d.auction_number + '</a></td>' +
                    '</tr>' +
                    '</table>';
            }

            // Add event listener for opening and closing details
            $('#auctionTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {

                    // Open this row
                    // console.log(JSON.parse(atob(tr.attr('data-name'))));
                    let auctionData = JSON.parse(atob(tr.attr('data-raw')));

                    if (!auctionData || auctionData === null || typeof auctionData === 'undefined') {
                        alert('Не удалось загрузить данные аукциона, пожалуйста, обновите страницу и попробуйте ещё раз');
                        return false;
                    }

                    row.child(format(auctionData)).show();
                    tr.addClass('shown');
                }
            });

            $('.dataTables_length').addClass('bs-select');


        });


    </script>

    <style>


        .hidden {
            visibility: hidden;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:before {
            bottom: .5em;
        }

        td.details-control {
            background: url('../images/open.png') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('../images/close.png') no-repeat center center;
        }

    </style>


@endsection

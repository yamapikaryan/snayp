<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Снайп') }}</title>


<?php if(  Route::currentRouteName() !== 'next.index' ){ ?>

    <script src="/js/jquery-3.4.1.min.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

{{--    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}



    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('css/addons/datatables.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('dropzone/dist/min/basic.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('dropzone/dist/min/dropzone.min.css')}}">
    <script src="{{asset('/dropzone/dist/min/dropzone.min.js')}}"></script>


    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">



    <?php }else{ ?>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
<?php } ?>




    <!-- Scripts -->



{{--    <script src="{{ asset('js/app.js') }}" defer></script>--}}


    <!-- Fonts -->


    <!-- Styles -->

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">Снайп</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto ml-5">
                <?php if(\Illuminate\Support\Facades\Auth::check()){ ?>
                <li class="nav-item active">
                    <a class="nav-link" href="/auctions/">Все конкурсы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mylist/">Мои конкурсы</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Обеспечения
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Заявок</a>
                        <a class="dropdown-item" href="#">Исполнения контрактов</a>
                        <a class="dropdown-item" href="#">Гарантийных обязательств</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Архив</a>
                </li>
                <li class="nav-item ml-5">
                    <a class="nav-link" href="/auctions/create">Создать заявку</a>
                </li>
            </ul>
{{--            <form class="form-inline my-2 my-lg-0">--}}
{{--                <input class="form-control mr-sm-2" type="search" placeholder="Номер аукциона" aria-label="search">--}}
{{--                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Поиск</button>--}}
{{--            </form>--}}
            <?php } ?>

            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Войти') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Зарегистрироваться') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Выйти') }}
                            </a>

                            @can('manage-users')
                                <a class="dropdown-item" href="{{route('admin.users.index')}}">
                                    Управление пользователями
                                </a>
                            @endcan
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>


        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @include('partials.alerts')
            @yield('content')
        </div>
    </main>
</div>

{{--<script type="text/javascript" src={{asset("js/jquery-3.4.1.min.js")}}></script>--}}
{{--<!-- Bootstrap tooltips -->--}}
{{--<script type="text/javascript" src={{asset("js/popper.min.js")}}></script>--}}
{{--<!-- Bootstrap core JavaScript -->--}}
{{--<script type="text/javascript" src={{asset("js/bootstrap.min.js")}}></script>--}}

<?php if(  Route::currentRouteName() !== 'next.index' ){ ?>

<link rel="stylesheet" type="text/css" src="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>


</body>
</html>
<?php } ?>

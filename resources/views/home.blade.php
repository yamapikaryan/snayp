@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Панель управления</div>

                <div class="card-body">
                    <form>
                        <input type="button" class="btn btn-primary" value="Создать заявку" onclick="window.location.href='/auctions/create'" />
                    </form>
                </div>
                <div>

                </div>
            </div>

        </div>
    </div>

@endsection

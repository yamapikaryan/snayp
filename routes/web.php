<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'can:manage-users'
], function(){
    Route::resource('/users', 'UsersController', ['except' => ['show', 'create', 'store',]]);
});

//Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:manage-users')->group(function (){
//
//});

Route::group(['prefix' => '/next', 'as' => 'next.', 'namespace' => 'Next'], function(){
    Route::get('/', 'FirstController@index')->name('index');
});

Route::get('/external', 'ExternalLinksController@index')->name('external');

Route::group(['middleware' => 'auth'], function(){
    Route::resource('/auctions', 'AuctionController');
    Route::post('/auctions/{id}/update', 'AuctionController@update')->name('auctions.update');;

    Route::get('/mylist', 'AuctionController@mylist');

    Route::get('/', 'HomeController@index')->name('home');

    Route::post('/auctions/autofill', 'AuctionController@autoFill')->name('auctions.autoFill');

    Route::post('/auctions/sendlist', 'AuctionController@sendList')->name('auctions.sendList');

    Route::post('/auctions/fileupload/', 'AuctionController@fileUpload')->name('auctions.fileUpload');

    Route::get('/auctions/files/zip', 'AuctionController@zipFiles')->name('auctions.zipFiles');



//    Route::get('/autotest', function () {
//        return view('autotest');
//    });

});

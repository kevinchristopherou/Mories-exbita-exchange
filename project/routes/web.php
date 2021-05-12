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

Route::group(['middleware' => ['ethereum.state', 'license.check', 'application.state']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/a/', 'HomeController@admin');
    Route::get('/preview', 'HomeController@preview');
    Route::get('/apidocs', 'HomeController@apidocs');
    Route::get('/terms', 'HomeController@terms');
    Route::get('/chart', 'HomeController@chart');
});

Route::group(['middleware' => ['ethereum.state']], function () {
    Route::get('/license/check', 'LicenseController@index')->name('license.check');
    Route::get('/license/activate', 'LicenseController@activate')->name('license.activate');
});

Route::get('/ethereum/check', 'EthereumStateController@index')->name('ethereum.check');
Route::get('/ethereum/activate', 'EthereumStateController@activate')->name('ethereum.activate');


Route::group(['middleware' => ['ethereum.state', 'license.check']], function () {

    Route::get('/application/check', 'ApplicationStateController@index')->name('application.check');
    Route::get('/application/activate', 'ApplicationStateController@activate')->name('application.activate');

});
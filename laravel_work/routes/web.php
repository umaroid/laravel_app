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

Route::get('/', function () {
    return view('welcome');
    //return 123;
});

Route::get('sample/mailable/preview', function(){
   return new App\Mail\SampleNotification(); 
});

Route::get('sample/mailable/send', 'SampleController@SampleNotification');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

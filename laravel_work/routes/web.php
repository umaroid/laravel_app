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

// http://{ホスト名}/admin/formにGETでアクセスすると、AdminBlogControllerのfromメソッドを実行する
// nameメソッドでエイリアスをつけることができる
// URL中の値を取り出したいときは、ルートパラメータを利用する。{}で囲んだ部分を取り出すことができる
// パラメータ名末尾の`?`は、任意のパラメータを表すもので、このパラメータはあっても無くても良い、ということになる
//Route::get('admin/form', 'AdminBlogController@form')->name('admin_form');
Route::get('admin/form/{article_id?}', 'AdminBlogController@form')->name('admin_form');
Route::post('admin/post', 'AdminBlogController@post')->name('admin_post');
Route::post('admin/delete', 'AdminBlogController@delete')->name('admin_delete');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

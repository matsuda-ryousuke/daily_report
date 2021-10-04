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

//メイン画面を表示する
Route::get('/', 'HomeController@main')->name('main');

// // <<日報管理関係ルーティング>>
Route::get('report/newsearch', 'DailyReportController@newsearch')->name('report.newsearch');
Route::match(['get', 'post'], 'report/search', 'DailyReportController@search')->name('report.search');

Route::resource('report', 'DailyReportController');
// 一覧を表示 => index
// 登録画面を表示 => create
// 登録実行 => store
// 差戻しの編集画面を表示 => edit
// 個別の記事を表示 => show
// 登録確認画面を表示
Route::post('report/confirm', 'DailyReportController@confirm')->name('report.confirm');
// 削除する
Route::post('report/{id}/delete', 'DailyReportController@delete')->name('report.delete');
// 履歴を表示
Route::get('report/{id}/log', 'DailyReportController@history')->name('report.history');

// <<ユーザー管理関係ルーティング>>
Auth::routes();
Route::get('auth/logout', 'Auth\LoginController@logout')->name('user.logout');

// システム管理者のみアクセス可能
Route::group(['middleware' => 'admin'], function () {
    // この中は、全てミドルウェアが適用されます。
});


// <<ユーザー情報取得ルーティング>>
Route::get('get_area/{group_id}', 'Auth\GetinfoController@getArea');
Route::get('get_client_by_area_group/{group_id}', 'Auth\GetinfoController@getClientByAreaGroup');
Route::get('get_client/{area_id}', 'Auth\GetinfoController@getClient');
Route::get('set_area_group_by_area/{area_id}', 'Auth\GetinfoController@setAreaGroupByArea');
Route::get('set_area/{client_id}', 'Auth\GetinfoController@setArea');
Route::get('set_area_group/{client_id}', 'Auth\GetinfoController@setAreaGroup');
Route::get('reset_area', 'Auth\GetinfoController@resetArea');
Route::get('reset_client', 'Auth\GetinfoController@resetClient');
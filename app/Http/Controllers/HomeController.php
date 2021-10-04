<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // ログイン後でないと閲覧出来ないようにミドルウェアを適用する
        $this->middleware('auth');
    }

    /**
     * メインメニューを表示する
     *
     * @param Request $request
     * @return view [2]日報検索画面
     */
    public function main(Request $request)
    {

        //cookieからセッションに最終ログイン時間を登録
        if (\Cookie::has('last_login')) {
            $l_login = \Cookie::get('last_login');
            \Session::put('last_login', $l_login);
        }

        // ログインユーザーの情報をcookieに登録
        $current = Auth::id();
        $userinfo = DB::table('users')
            ->where('id', $current)
            ->select('name')
            ->get();
        foreach ($userinfo as $uinfo) {
            \Session::put('username', $uinfo->name);
        }

        // viewを呼び出す
        return redirect(route('report.index'));
    }
}
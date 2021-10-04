<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // 認証columnを社員コードにするように変更する
    public function username()
    {
        // 社員コードを返す
        return 'email';
    }

    // showLoginControllerをオーバーライドする
    public function showLoginForm(Request $request)
    {

        // viewに渡すblade用データ
        $title = 'ログインページ';
        $css = 'base.css';

        return view('Auth.login', compact('title', 'css'));
    }

    // ログイン後の遷移先を指定する
    public function redirectPath()
    {
        // ログイン時にcookieにログイン時間を記録
        $login = date('m/d H:i');
        \Cookie::queue('last_login', $login, 5);

        $current = Auth::id();
        return '/';
    }

    // 同一アカウントへの多重ログインを禁止する
    protected function authenticated(Request $request, $user)
    {
        Auth::logoutOtherDevices($request->input('password'));
    }

    // /**
    //  * パスワードの認証に使うカラムを変更する
    //  *
    //  * @return string
    //  */
    // public function getAuthPassword() 
    // {
    //     return $this->user_pass; // 対象のカラム名
    // }
}
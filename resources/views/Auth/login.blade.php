@extends('common.layout')

@section('page_css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link rel="stylesheet" href="{{ asset('css/main.css') }}">
@endsection

@section('jq_plugins')
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
@endsection

@section('page_js')
<script src="{{ asset('js/auth/login.js') }}"></script>
@endsection

@section('body')
<div class="pw-form">
    @if (isset($errors))
    <ul id="error_box">
        @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>
    @endif

    <div id="form">
        <form action="{{route('login')}}" method="post" class="pw-form-container">
            @csrf
            <p class="form-title">ログイン</p>
            <p>メールアドレス</p>
            <p class="mail"><input type="email" name="email" class="field" id="email" autofocus autocomplete="email"
                    maxlength="50" required /></p>
            <p>パスワード</p>
            <p class="pass"><input type="password" name="password" class="field password" id="js-password" minlength="8"
                    maxlength="16" pattern="[a-zA-Z0-9]+" onpaste="return false" required /></p>
            <p class="check"><label><input type="checkbox" id="js-passcheck" />パスワードを表示</label></p>
            <p class="submit"><button type="submit" class="login-button" id="login_submit"></button></p>
        </form>
    </div>
</div>
@endsection
<!doctype html>
<html lang="ja">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-store, max-age=0">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('/css/destyle.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/common2.css') }}">
    @yield('page_css')

    <script src="{{ asset('/js/jquery-3.5.1.min.js') }}"></script>
    @yield('jq_plugins')
    <script src="{{ asset('/js/utility.js') }}"></script>
    @yield('page_js')

    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('/img/fabi.png') }}" type="image/png">
</head>

<body>
    <header>
        <p class="homelink"><a href="{{route('main')}}"><span>xxx</span></a></p>

        @if(Auth::check())
        <p class="userdisp">
            {{-- {{ Session::get('dep_name') }} --}}
            {{ Session::get('username') }}@if(Session::has('last_login'))（最終ログイン：{{ Session::get('last_login')}}）@endif
        </p>
        <ul id="pclist">
            <li class="odd"><a href="{{route('report.create')}}"><img src="{{ asset('/img/menu04.png') }}"
                        alt="日報登録"></a></li>
            <li class="even"><a href="{{route('report.index')}}"><img src="{{ asset('/img/menu03.png') }}"
                        alt="検索･日報一覧"></a></li>
            <li class="logout"><a href="{{route('user.logout')}}"><img src="{{ asset('/img/logout.png') }}"
                        alt="ログアウト"></a></li>
        </ul>
        <div id="nav-drawer">
            <input id="nav-input" type="checkbox" class="nav-unshown">
            <label id="nav-open" for="nav-input"><span></span></label>
            <label class="nav-unshown" id="nav-close" for="nav-input"></label>
            <div id="nav-content">

                <ul id="splist">
                    <li><a href="{{route('report.create')}}">日報登録</a></li>
                    <li><a href="{{route('report.index')}}">検索･日報一覧</a></li>
                    <li><a href="{{route('user.logout')}}">ログアウト</a></li>
                </ul>
            </div>
        </div>
        @endif
    </header>
    <section class="main">
        @yield('body')
    </section>
    <footer class='tac'>
        <small>xxx</small>
    </footer>
</body>

</html>
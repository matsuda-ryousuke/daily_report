@extends('common.layout')

@section('page_css')

<link rel="stylesheet" href="{{ asset('css/main.css') }}">


@endsection

@section('jq_plugins')
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/scroll_to_top.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>

<script src="{{ asset('js/'.$js) }}"></script>

@endsection

@section('page_js')
<script src="{{ asset('js/report/list.js') }}"></script>
@endsection

@section('tagu')
{{$tagu}}
@endsection

@section('title')
{{$title}}
@endsection

@section('title1')
{{$title1}}
@endsection



@section('body')

<!-- フラッシュメッセージ -->
@if (session('err_msg'))
<div class="alert alert-danger">
    {{ session('err_msg') }}
</div>
@elseif (session('msg'))
<div class="alert alert-success">
    {{ session('msg') }}
</div>
@endif

@if ($errors->any())
<div class="">
    <ul id="error_box">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<a class="btn-all-report" href="{{url('/report/search')}}"><img src="{{ asset('img/btn08.png') }}" alt="日報検索"></a>

<section class="sec-contents report-search">
    <span class="newreport"><a href="{{url('/report/create')}}"><img class="img-new-icon"
                src="{{ asset('img/newreport_icon.png') }}" alt="日報登録"></a></span>
    <div class="search-head js-cmn-toggle">
        <div class="btn-toggle">
            <p class="search-open-img">ボタン</p>
        </div>
    </div>
    <div class="search-contents" style="display: none;">

        <form action="{{url('/report/search')}}" method="GET">
            @csrf

            <div class="search-item-wrap">
                <div class="search-item">
                    <div class="search-ttl">営業者名</div>
                    <div class="search-box">

                        <select name="user" id="user">
                            <option value=0>全件取得</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}" @if($user->id == $search['user']) selected
                                @endif>{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="search-item">
                    <div class="search-ttl">エリアグループ</div>
                    <div class="search-box">
                        <select id="area_group" name="area_group">
                            <option value=0>全件取得</option>
                            @foreach($area_groups as $area_group)
                            <option value="{{$area_group->group_id}}" @if($area_group->group_id ==
                                $search['area_group']) selected @endif>{{$area_group->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="search-item">
                    <div class="search-ttl">エリア</div>
                    <div class="search-box">
                        <select id="area" name="area">
                            <option value=0>全件取得</option>

                            @foreach($areas as $area)
                            <option value="{{$area->area_id}}" @if($area->area_id == $search['area']) selected
                                @endif>{{$area->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="search-item">
                    <div class="search-ttl">営業先</div>
                    <div class="search-box">
                        <select id="client" name="client">
                            <option value=0>全件取得</option>

                            @foreach($clients as $client)
                            <option value="{{$client->client_id}}" @if($client->client_id == $search['client'])
                                selected @endif>{{$client->company_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="search-item">
                    <div class="search-ttl">期間</div>
                    <div class="search-box">
                        <input class="long-box" type="date" name="after" id="after" class="date" max="{{$today}}"
                            min="2007-10-01" size="10" maxlength="10" value="{{$search['after']}}">
                        <p class="term-center">〜</p>
                        <input class="long-box" type="date" name="before" id="before" class="date" max="{{$today}}"
                            min="2007-10-01" size="10" maxlength="10" value="{{$search['before']}}">
                    </div>
                </div>


                <div class="search-item">
                    <div class="search-ttl">キーワード検索</div>
                    <div class="search-box">
                        <input class="long-box" class="keyword" type="search" name="visit_contents" id="keyword"
                            maxlength="100" value="{{$search['visit_contents']}}">
                    </div>
                </div>


                <div class="search-item">
                    <div class="search-box search-new">
                        <label class="search-new-check"><input type="checkbox" name="new" id="new" value=true
                                @if($search['new']==true) checked="checked" @endif>未読</label>
                        <label class="search-new-term"><input type="number" min="0" max="30"
                                @if(!($search['new']==true)) disabled @endif class="short-box" id="newdays"
                                name="newdays" value="{{$search['newdays']}}">日前</label>
                    </div>
                </div>

                <div class="search-item">
                    <div class="search-box search-updown">
                        表示：
                        <label><input type="radio" name="updown" id="down" value="0" @if($search['updown']!=1)
                                checked="checked" @endif>降順</label>
                        <label><input type="radio" name="updown" id="up" value="1" @if($search['updown']==1)
                                checked="checked" @endif>昇順</label>
                    </div>
                </div>

            </div>

            <div class="search-btn">
                <button class="btn-search btn-search-reset" id="btn-search-reset" type="button" onclick="">リセット</button>
                <button class="btn-search btn-search-submit" type="submit" onclick="">検索</button>
            </div>

        </form>
    </div>
</section>

<section class="sec-contents report-list">
    <div class="h1-wrap">
        <h1 class="search-list">
            <span class="reports-list-ttl">@yield('title1')</span>
        </h1>
    </div>

    @if(isset($count))
    <h2 class="search-result">検索結果：{{$count}}件ヒットしました</h2>
    @endif

    <ul class="js-list-hasPage">
        @if (isset($reports))
        @foreach($reports as $report)
        <li class="js-list-item list-indivi">
            <div class="list-item client-area"><span class="ttl"></span><span
                    class="corp-text-cut">{{$report -> company_name}}</span><span class="">{{$report -> a_name}}</span>
            </div>
            <div class="list-item name-date"><span class="ttl">営業者：</span><span
                    class="list-item name">{{$report -> name}}</span><span
                    class="list-item term">{{$report -> visit_date->format('Y年n月j日')}}</span></div>
            <div class="list-item summary text-cut"><span class="ttl">概要：</span><span
                    class="list-item">{{$report -> title}}</span>
            </div>
            <div class="large-list-item large-screen-list">
                <span class="large-list-item large-term">{{$report -> visit_date->format('Y年n月j日')}}</span>
                <span class="large-list-item large-name">{{$report -> name}}</span>
                <span class="large-list-item large-summary large-text-cut">{{$report -> title}}</span>
            </div>
            <div class="btn-detail">
                <a class="btn-detail-img" href="{{url('/report')}}/{{$report->unique_report_id}}"></a>
            </div>
        </li>
        @endforeach
        @endif
    </ul>

    <div class="pagination-wrap">
        {{ $reports->appends(request()->query())->links('vendor/pagination/reportpage') }}
    </div>

    <div id="scroll_to_top" class="button">
        <p>TOPへ</p>
    </div>
</section>
@endsection
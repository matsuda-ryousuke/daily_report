@extends('common.layout')

@section('page_css')

<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
<link rel="stylesheet" href="{{ asset('css/dailyreport.css') }}">

@endsection

@section('jq_plugins')
<script src="{{ asset('js/jquery.tablesorter.min.js') }}"></script>
@endsection

@section('page_js')
<script src="{{ asset('js/report/confirm.js') }}"></script>
<script src="{{ asset('js/auth/admin.js') }}"></script>
<script src="{{ asset('js/scroll_to_top.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
@endsection

@section('tagu')
{{$tagu}}
@endsection

@section('title')
{{$title}}
@endsection

@section('body')
<section>
    <div class="h1-wrap">
        <h1>@yield('title')</h1>
    </div>


    <ul>
        <li class="report-detail-top">
            <div class="detail-item detail-2column">
                <span>
                    <span class="ttl">訪問日時:</span>
                    <span>{{$visit_date}}</span>
                </span>
            </div>
            <div class="detail-item"><span class="ttl">営業先:</span><span class="">{{$report -> company_name}}
                    [{{$report -> area_name}}]</span></div>
            <div class="detail-item"><span class="ttl">ご担当者様:</span><span class="">{{$report -> client_name}}
                    [{{$report -> client_dep}}]</span></div>
            <div class="detail-item"><span class="ttl">概要:</span><span class="">{{$report -> title}}</span></div>
            </div>
        </li>
        <li class="report-detail-center">
            <div class="detail-item">
                <span class="ttl">[ 業務内容 ]</span>
                <br>
                <span class="detail-contents">
                    {!! nl2br(e($report -> visit_contents)) !!}
                </span>
            </div>
            <div class="detail-item">
                <span class="ttl">[ 次の展開 ]</span>
                <br>
                <span class="detail-contents">
                    {!! nl2br(e($report -> next_step)) !!}
                </span>
            </div>
        </li>
    </ul>
    <div class="bottom-btn pg-confirm-btn">
        <form action="{{route('report.store')}}" method="POST">
            @csrf

            <input type="hidden" name="visit_date" value="{{$report -> visit_date}}">
            <input type="hidden" name="area_group" value="{{$report -> area_group}}">
            <input type="hidden" name="area" value="{{$report -> area}}">
            <input type="hidden" name="client" value="{{$report -> client}}">
            <input type="hidden" name="client_name" value="{{$report -> client_name}}">
            <input type="hidden" name="client_dep" value="{{$report -> client_dep}}">
            <input type="hidden" name="title" value="{{$report -> title}}">
            <input type="hidden" name="visit_contents" value="{{$report -> visit_contents}}">
            <input type="hidden" name="next_step" value="{{$report-> next_step }}">
            <input type="hidden" name="submit" value="修正する">

            <div class="btn-detail">
                <input class="btn-return-img cmn-btn-image" type="submit" value="">
            </div>
        </form>
        <form action="{{route('report.store')}}" method="POST" onSubmit="return checkSubmit()">
            @csrf
            <div class="btn-detail">
                <input class="btn-register-img cmn-btn-image" type="submit" name="submit" value="" autofocus>
            </div>
        </form>
    </div>

    <script>
        function checkSubmit()
    {
        if(window.confirm('日報を登録してよろしいですか？')){
            return true;
        } else {
            return false;
        }
    }
    </script>
</section>
@endsection
@extends('common.layout')

@section('page_css')

<link rel="stylesheet" href="{{ asset('css/detail.css') }}">

@endsection

@section('jq_plugins','')

@section('page_js')
<script src="{{ asset('js/report/confirm.js') }}"></script>
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
<section class="report-contents report-top">
    <div class="h1-wrap report-detail-hdwrap">
        <h1 class="report-detail1 report-detail-hd">
            <span class="detail-ttl">日報詳細</span>
        </h1>
        @if($is_auth)
        <!-- 自分以外の日報では非表示 -->
        <div class="h1-iconbtn-wrap">
            <span class="h1-list pen">
                <a href="{{url('/report')}}/{{$report_data->unique_report_id}}/edit"><img
                        src="{{ asset('img/pen.svg') }}" alt="編集" title="日報編集" class="img-icon"></a>
            </span>
            <span class="h1-list dustbox">
                <form class="delete" method="POST" action="{{ route('report.delete', $report_data->unique_report_id) }}"
                    onSubmit="return deleteReport()">
                    @csrf
                    <input type="image" class="img-icon" src="{{ asset('img/dustbox.svg') }}" alt="削除" title="日報削除"
                        onclick="">
                </form>
            </span>
        </div>
        @endif
        <!-- 自分以外の日報では非表示ここまで -->
    </div>
    <ul>
        <li class="report-detail-top">
            <div class="detail-item detail-2column">
                <span>
                    <span class="ttl">作成者:</span>
                    <span>{{$report_data -> user_name}}</span>
                </span>
            </div>
            <div class="detail-item detail-2column">
                <span>
                    <span class="ttl">訪問日時:</span>
                    <span>{{$report_data -> visit_date->format('Y年n月j日 G時i分')}}</span>
                </span>
            </div>
            <div class="detail-item">
                <span class="ttl">営業先:</span>
                <span class="">{{$report_data -> company_name}} [{{$report_data -> area_name}}]</span>
            </div>
            <div class="detail-item">
                <span class="ttl">ご担当者様:</span>
                <span class="">{{$report_data -> client_name}} [{{$report_data -> client_dep}}]</span>
            </div>
            <div class="detail-item">
                <span class="ttl">概要:</span>
                <span class="">{{$report_data -> title}}</span>
            </div>
        </li>
        <li class="report-detail-center">
            <div class="detail-item">
                <span class="ttl">[ 業務内容 ]</span>
                <br>
                <span class="detail-contents todaywork">{!! nl2br(e($report_data -> visit_contents)) !!}</span>
            </div>
            <div class="detail-item">
                <span class="ttl">[ 次の展開 ]</span>
                <br>
                <span class="detail-contents todaywork">{!! nl2br(e($report_data -> next_step)) !!}</span>
            </div>
        </li>
        <li class="report-detail-bottom">
            <div class="detail-item">
                <span class="ttl">登録日時:</span>
                <span class="">
                    {{ $report_data -> created_at->format('Y年n月j日 G時i分') }}
                </span>
            </div>
            <div class="detail-item">
                <span class="ttl">閲覧者:</span>
                <span class="list-item name">{{$count}}名</span>
                <div class="viewer js-cmn-toggle">
                    <button class="detail-button btn-detail-img" type="button"></button>
                </div>
                <ul class="detail-viewer">
                    @foreach($readers as $reader)
                    <li class="viewer-name">{{$reader -> name}}</li>
                    @endforeach
                </ul>
            </div>
        </li>
    </ul>
    <div class="bottom-btn">
        <div class="btn-detail">
            <form @if ($search) action="{{url('/report/search')}}" @else action="{{url('/report')}}" @endif
                method="GET">
                <input type='hidden' name='page' value="{{$currentPage}}" />
                <button class="btn-return-img cmn-btn-image" type="submit" name="back" value="true"></button></form>
        </div>
        @if($report_data->status != 0)
        <div class="btn-detail">
            <button class="btn-history-img cmn-btn-image"
                onclick="location.href='{{url('/report')}}/{{$report_data->unique_report_id}}/log'">
            </button>
        </div>
        @endif
    </div>

    <script type="text/javascript">
        function deleteReport() {
        // 確認ダイアログの表示
        if (window.confirm('日報を削除しますか?')) {
            return true;
        } else {
            return false;
        }
    }
    </script>

</section>
@endsection
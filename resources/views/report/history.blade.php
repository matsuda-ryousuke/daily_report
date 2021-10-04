@extends('common.layout')

@section('page_css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('jq_plugins','')

@section('page_js')
<script src="{{ asset('js/report/confirm.js') }}"></script>
<script src="{{ asset('js/scroll_to_top.js') }}"></script>
@endsection

@section('tagu')
{{$tagu}}
@endsection

@section('title')
{{$title}}
@endsection

@section('body')
<section class="report-contents report-top">
    @foreach ($reports as $report_data)
    <div class="h1-wrap">
        <h1 class="report-detail1 report-derail2">
            <span class="detail-ttl">日報編集履歴
                @if($report_data -> status != 0)
                [{{$report_data -> status}}回目]
                @else
                [オリジナル]
                @endif
            </span>
            <span class="detail-name">{{$report_data -> user_name}}</span>
        </h1>
    </div>
    <ul>
        <li class="report-detail-top">
            <div class="detail-item detail-2column">
                <span>
                    <span class="ttl">訪問日時:</span>
                    <span>{{$report_data -> visit_date-> format('Y年n月j日 G時i分')}}</span>
                </span>
            </div>
            <div class="detail-item">
                <span class="ttl">営業先:</span>
                <span class="">{{$company_name}}[{{$area_name}}]</span>
            </div>
            <div class="detail-item"><span class="ttl">ご担当者様:</span>
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
                <span class="detail-contents">
                    {!! nl2br(e($report_data -> visit_contents)) !!}
                </span>
            </div>
            <div class="detail-item">
                <span class="ttl">[ 次の展開 ]</span>
                <br>
                <span class="detail-contents">
                    {!! nl2br(e($report_data -> next_step)) !!}
                </span>
            </div>
        </li>
        <li class="report-detail-bottom">
            <div class="detail-item">
                <span class="ttl">登録日時:</span>
                <span class="">{{$report_data -> created_at-> format('Y年n月j日 G時i分')}}</span>
            </div>
        </li>
    </ul>
    <div class="bottom-btn history-page">
        <div class="pagination-wrap">
            {{ $reports->links() }}
        </div>
        <div class="btn-detail">
            <a class="btn-return-img cmn-btn-image" href="{{url('/report')}}/{{$report_data -> unique_report_id}}"></a>
        </div>
    </div>
    @endforeach
</section>
@endsection
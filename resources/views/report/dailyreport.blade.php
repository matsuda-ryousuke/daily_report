@extends('common.layout')

@section('page_css')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
<link rel="stylesheet" href="{{ asset('css/dailyreport.css') }}">

@endsection

@section('jq_plugins','')

@section('page_js')
<script src="{{ asset('js/report/dailyreport.js') }}"></script>
<script src="{{ asset('js/'.$js) }}"></script>
@endsection

@section('title')
{{$title}}
@endsection

@section('body')
<section>
    <div class="container">

        <h1>{{$title}}</h1>
        @if ($errors->any())
        <div class="">
            <ul id="error_box">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="report-form" action="{{route('report.confirm')}}" method="post">
            @csrf
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="visit_date">訪問日時：</label>
                <div class="col-sm-8">
                    <input id="visit_date" type="datetime-local" class="form-control form-control-lg" name="visit_date"
                        value="{{ old('visit_date' , $report_data -> visit_date ) }}" step="60" max="{{$today}}"
                        min="2007-10-01T00:00" required>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-4 control-label" for="area_group">エリアグループ：</label>
                <div class="col-sm-8">
                    <select id="area_group" name="area_group" class="form-control form-control-lg">
                        <option value=0>選択してください</option>
                        @foreach($area_groups as $area_group)
                        <option value="{{ $area_group->group_id }}" @if($area_group->group_id ==
                            old('area_group') || $area_group->group_id ==
                            $report_data->area_group) selected @endif>{{$area_group->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4 control-label" for="area">エリア：</label>
                <div class="col-sm-8">
                    <select id="area" name="area" class="form-control form-control-lg">

                        @if($report_data->edit || $report_data->back)
                        @else
                        <option value="" disabled selected>選択してください</option>
                        @endif

                        @foreach($areas as $area)
                        <option value="{{ $area->area_id }}" @if($area->area_id ==
                            old('area') || $area->area_id ==
                            $report_data->area) selected @endif>{{$area->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4 control-label" for="client">営業先：</label>
                <div class="col-sm-8">
                    <select id="client" name="client" class="form-control form-control-lg" required min="1">
                        @if($report_data->edit || $report_data->back)
                        @else
                        <option value="" disabled selected>選択してください</option>
                        @endif
                        @foreach($clients as $client)
                        <option value="{{ $client->client_id }}" @if($client->client_id ==
                            old('client') || $client->client_id ==
                            $report_data->client) selected @endif>{{$client->company_name}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4 control-label" for="clientName">ご担当者様：</label>
                <div class="col-sm-8">
                    <input id="clientName" class="form-control form-control-lg" name="client_name" maxlength="20"
                        required placeholder="内容を入力してください"
                        value="{{ old('client_name' , $report_data -> client_name ) }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label" for="clientDep">ご担当者様部署：</label>
                <div class="col-sm-8">
                    <input id="clientDep" class="form-control form-control-lg" name="client_dep" maxlength="20" required
                        placeholder="内容を入力してください" value="{{ old('client_dep' , $report_data -> client_dep ) }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4 control-label" for="reportData">日報概要：</label>
                <div class="col-sm-8">
                    <input id="reportData" class="form-control form-control-lg" name="title" rows="1" cols="40" required
                        maxlength="60" placeholder="内容を入力してください" value="{{ old('title' , $report_data -> title ) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="visitContents">
                    業務内容：<span class="cnt_area"><span class="now_cnt">0</span> /
                        360</span>
                </label>
                <textarea id="visitContents" class="form-control form-control-lg" rows="4" cols="40" maxlength="360"
                    required name="visit_contents"
                    placeholder="内容を入力してください">{{ old('visit_contents' , $report_data -> visit_contents ) }}</textarea>
            </div>
            <div class="form-group">
                <label class="control-label" for="nextStep">次の展開：<span class="cnt_area2"><span class="now_cnt2">0</span>
                        /
                        360</span></label>
                <textarea id="nextStep" class="form-control form-control-lg" rows="4" cols="40" maxlength="360" required
                    name="next_step"
                    onkeydown="if(event.ctrlKey&&event.keyCode==13){document.getElementById('submit_form').click();return false};"
                    placeholder="内容を入力してください">{{ old('next_step' , $report_data -> next_step ) }}</textarea>
            </div>

            <div class="bottom-btn">
                <div class="btn-detail">
                    <a class="btn-return-img cmn-btn-image" @if ($search && !($report_data->edit))
                        href="{{url('/report/search')}}?back=true&page={{$currentPage}}" @elseif($report_data->edit)
                        href="{{route('report.show', ['report' => $report_data->unique_report_id])}}"
                        @else href="{{url('/report')}}?back=true&page={{$currentPage}}" @endif></a>
                </div>
                <div class='btn-detail'>
                    <input id='submit_form' class="btn-confirm-img cmn-btn-image" type='button' value="">
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
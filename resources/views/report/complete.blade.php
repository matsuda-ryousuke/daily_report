@extends('common.layout')

@section('jq_plugins','')

@section('page_js')
<script src="{{ asset('js/report/complete.js') }}"></script>
@endsection

@section('tagu')
{{$tagu}}
@endsection

@section('body')
<section class="tac">
  <p class="complete tac">登録が完了しました</p>
</section>
@endsection
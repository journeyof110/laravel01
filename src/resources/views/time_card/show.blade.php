@extends('adminlte::page')

@section('title', 'AdminLTE|タイムカードの詳細')

@section('content_header')
  @include('components.alert')
  <h1 class="m-0 text-dark">タイムカードの詳細</h1>
@stop

@section('content')
<div class="container-fluid">
  <div class="card card-default">
    <div class="card-header d-flex p-0">
      <h3 class="card-title p-3">詳細</h3>
      <ul class="nav nav-pills ml-auto p-2">
        <li class="nav-item">
          <a class="btn btn-block btn-default" href="{{route('time_card.edit', ['timeCard' => $timeCard->id])}}">
            <i class="fas fa-edit"></i>
            編集
          </a>
        </li>
      </ul>
    </div>
    <div class="card-body col-md-12">
      <div class="form-group row">
        <div class="col-md-2">
          <label>年月日</label>
        </div>
        <div class="col-md-3 input-group date" id="datetimepicker" data-target-input="nearest">
          {{$timeCard->date}}
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-2">
          <label>開始時刻</label>
        </div>
        <div class="col-md-3 input-group date input-time">
          {{$timeCard->start_time}}
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-2">
          <label>終了時刻</label>
        </div>
        <div class="col-md-3 input-group date input-time">
          {{$timeCard->end_time}}
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-2">
          <label>メモ</label>
        </div>
        <div class="col-md-10 input-group date">
          {{$timeCard->memo}}
        </div>
      </div>
    </div>
    <div class="card-footer">
      <a href="{{route('time_card')}}" class="btn btn-secondary">戻る</a>
    </div>
  </div>
</div>
@endsection
@php
  $startOptions = [
    'type' => 'submit',
    'class' => 'btn btn-app bg-lightblue',
    'name' => 'hasClieckedStart',
    'formaction' => route('time_card.start'),
    'value' => true,
  ];
  $endOptions = [
    'type' => 'submit',
    'class' => 'btn btn-app bg-maroon',
    'name' => 'hasClieckedEnd',
    'formaction' => route('time_card.end', ['timeCard' => optional($latestTimeCard)->id]),
    'value' => true,
  ];

  if (isset($latestTimeCard->id) && is_null($latestTimeCard->end_time)) {
    $startOptions['disabled'] = 'disabled';
    $startOptions['value'] = false;
    $startTime = $latestTimeCard->start_datetime;
  } else {
    $endOptions['disabled'] = 'disabled';
    $endOptions['value'] = false;
    $startTime = null;
  }
@endphp

@extends('adminlte::page')

@section('title', 'AdminLTE|タイムカード')

@section('content_header')
  @include('components.alert')
  <h1 class="m-0 text-dark">タイムカード</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">入力フォーム</h3>
              </div>
              <div class="card-body">
                {!! Form::open() !!}
                  <div class="row">
                    <div class="col-sm-3">
                      {!! Form::button('<i class="fas fa-play"></i>開始', $startOptions) !!}
                      <h5>{{$startTime}}</h5>
                    </div>
                    <div class="col-sm-3">
                      {!! Form::button('<i class="fas fa-stop"></i>終了', $endOptions) !!}
                    </div>
                    <div class="col-sm-6">
                      {!! Form::textarea('memo', $latestTimeCard->memo, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'メモ']) !!}
                    </div>
                  </div>
                {!! Form::close() !!}
              </div>
            </div>
            <div class="card">
              <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">タイムカード</h3>
                <ul class="nav nav-pills ml-auto p-2">
                  <li class="nav-item">
                    <a class="btn btn-block btn-default" href="{{route('time_card.create')}}">
                      <i class="fas fa-pen"></i>
                      作成
                    </a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>日 (曜日)</th>
                      <th>開始時刻</th>
                      <th>終了時刻</th>
                      <th>カテゴリ</th>
                      <th class="col-sm-2 text-center">アクション</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($timeCards as $timeCard)
                      <tr>
                        <td>{{$timeCard->dayAndDayName}}</td>
                        <td>{{$timeCard->start_time}}</td>
                        <td>{{$timeCard->end_time}}</td>
                        <td>{{$timeCard->categury_id}}</td>
                        <td class="col-sm-2 text-center ">
                          <a class="link-muted m-3" href="{{route('time_card.show', ['timeCard' => $timeCard->id])}}">
                            <i class="fas fa-file-alt"></i>
                          </a>
                          <a class="link-muted m-3" href="{{route('time_card.show', ['timeCard' => $timeCard->id])}}">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a class="link-muted m-3" href="{{route('time_card.show', ['timeCard' => $timeCard->id])}}">
                            <i class="fas fa-trash"></i>
                          </a>
                        </td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script>
      $("[name='option-param']").bootstrapSwitch();
    </script>
@stop

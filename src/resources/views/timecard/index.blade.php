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
    'formaction' => route('time_card.end', ['timeCard' => optional($timeCard)->id]),
    'value' => true,
  ];

  if (isset($timeCard->id) && is_null($timeCard->end_time)) {
    $startOptions['disabled'] = 'disabled';
    $startOptions['value'] = false;
    $startTime = $timeCard->start_datetime;
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
                入力フォーム
              </div>
              <div class="card-body">
                {!! Form::open([]) !!}
                  <div class="row">
                      <div class="col-sm-6">
                        {!! Form::button('<i class="fas fa-play"></i>開始', $startOptions) !!}
                        <h5>{{$startTime}}</h5>
                      </div>
                      <div class="col-sm-6">
                          {!! Form::button('<i class="fas fa-stop"></i>終了', $endOptions) !!}
                      </div>
                  </div>
                  {!! Form::close() !!}
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                タイムカード
              </div>
              <div class="card-body">
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

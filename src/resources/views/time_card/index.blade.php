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
    'formaction' => route('time_card.end', ['time_card' => optional($latestTimeCard)->id]),
    'value' => true,
  ];

  $categoryIdOptions = [
    'class' => 'custom-select '. (!$errors->has('category_id') ? '' : 'is-invalid'),
  ];

  $memoOptions = [
    'class' => 'form-control ' . (!$errors->has('memo') ? '' : 'is-invalid'),
    'rows' => 3,
    'placeholder' => 'メモ'
  ];

  if (isset($latestTimeCard->id) && is_null($latestTimeCard->end_time)) {
    $startOptions['disabled'] = 'disabled';
    $startOptions['value'] = false;
    // $startTime = $latestTimeCard->start_datetime;
  } else {
    $endOptions['disabled'] = 'disabled';
    $endOptions['value'] = false;
    // $startTime = null;
  }
@endphp

@extends('layouts.app')

@section('title', 'AdminLTE|タイムカード')

@section('content-title', 'タイムカード')

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
                  <div class="col-sm-1">
                    {!! Form::button('<i class="fas fa-play"></i>開始', $startOptions) !!}
                  </div>
                  <div class="col-sm-1">
                    {!! Form::button('<i class="fas fa-stop"></i>終了', $endOptions) !!}
                  </div>
                  <div class="col-sm-3">
                    {!! Form::select('category_id', $categories, optional($latestTimeCard)->category_id, $categoryIdOptions) !!}
                    <span class="error invalid-feedback">
                      @error('category_id') {{$message}} @enderror
                    </span>
                  </div>
                  <div class="col-sm-7">
                    {!! Form::textarea('memo', $latestTimeCard->memo ?? '', $memoOptions) !!}
                    <span class="error invalid-feedback">
                      @error('category_id') {{$message}} @enderror
                    </span>
                  </div>
                </div>
                {!! Form::close() !!}
              </div>
              <div class="card-footer">
                <h5>{{optional($latestTimeCard)->date_and_start_time_format}}</h5>
              </div>
            </div>
            <div class="card">
              <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">タイムカード一覧</h3>
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
                      <tr class="{{((session('endId') ?? '') == $timeCard->id) ? 'bg-maroon' : (((session('startId') ?? '') == $timeCard->id) ? 'bg-lightblue' : '')}}">
                        <td>{{$timeCard->day_and_day_name_format . ($endId ?? '') . ($startId ?? '') }}</td>
                        <td>{{$timeCard->start_time}}</td>
                        <td>{{$timeCard->end_time}}</td>
                        <td>{{$timeCard->category->name}}</td>
                        <td class="col-sm-2 text-center ">
                          <a class="btn btn-default" href="{{route('time_card.show', ['time_card' => $timeCard->id])}}" >
                            <i class="fas fa-file-alt"></i>
                          </a>
                          <a class="btn btn-default" href="{{route('time_card.edit', ['time_card' => $timeCard->id])}}">
                            <i class="fas fa-edit"></i>
                          </a>
                          <button type="button" class="btn btn-default btn-delete" data-toggle="modal" data-target="#modal" data-daytime='{{$timeCard->date_and_time_format}}' data-link="{{route('time_card.destroy', ['time_card' => $timeCard->id])}}">
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                      @endforeach
                      {{ $timeCards->links('pagination::bootstrap-4') }}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
    @include('time_card.components.modal')
@stop

@section('js-for-page')
<script src="{{ mix('js/time_card/modal.js') }}?={{config('version.number')}}"></script>
@stop

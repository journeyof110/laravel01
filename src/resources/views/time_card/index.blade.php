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
                  <div class="col-sm-1">
                    {!! Form::button('<i class="fas fa-play"></i>開始', $startOptions) !!}
                  </div>
                  <div class="col-sm-1">
                    {!! Form::button('<i class="fas fa-stop"></i>終了', $endOptions) !!}
                  </div>
                  <div class="col-sm-3">
                    {!! Form::select('category_id', $categories, $latestTimeCard, $categoryIdOptions) !!}
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
                <h5>{{$startTime}}</h5>
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
                        <td>{{$timeCard->dayAndDayName . ($endId ?? '') . ($startId ?? '') }}</td>
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
                          <button type="button" class="btn btn-default btn-delete" data-toggle="modal" data-target="#modal" data-daytime='{{$timeCard->dayAndTime}}' data-link="{{route('time_card.destroy', ['time_card' => $timeCard->id])}}">
                            <i class="fas fa-trash"></i>
                          </button>
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
    <div class="modal fade" id="modal" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">削除の確認</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <p>タイムカードデータを削除しますか？</p>
            <blockquote class="quote-danger">
              <small id="modal-timecard-day"></small>
            </blockquote>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
            {{ Form::open(['method' => 'delete']) }}
              {!! Form::submit('削除する', ['class' => 'btn btn-primary']) !!}
            {{ Form::close() }}
          </div>
        </div>
      </div>
    </div>
@stop

@section('adminlte_js')
  <script>
    // モーダルにパラメータ渡し
    $('.btn-delete').on('click', function (event) {
      var dayTime = $(this).data('daytime');
      var link = $(this).data('link');
      $('#modal-timecard-day').html(dayTime);
      $('.modal-footer form').attr('action', link);
    });
  </script>
@stop

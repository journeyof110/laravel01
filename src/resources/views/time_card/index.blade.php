@php
  $startOptions = [
    'type' => 'submit',
    'class' => 'btn btn-app bg-lightblue card-loading',
    'name' => 'hasClieckedStart',
    'formaction' => route('time_card.start'),
    'value' => true,
  ];
  $endOptions = [
    'type' => 'submit',
    'class' => 'btn btn-app bg-maroon card-loading',
    'name' => 'hasClieckedEnd',
    'formaction' => route('time_card.end', ['time_card' => optional($latestTimeCard)->id]),
    'value' => true,
  ];

  $categoryIdOptions = [
    'class' => 'custom-select '. (!$errors->has('category_id') ? '' : 'is-invalid'),
  ];

  $memoOptions = [
    'class' => 'form-control ' . (!$errors->has('memo') ? '' : 'is-invalid'),
    'rows' => 10,
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
            <div class="card card-olive card-outline card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-pills ml-auto p-2" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-input-tab" data-toggle="pill" href="#custom-tabs-four-input" role="tab" aria-controls="custom-tabs-four-input" aria-selected="true">
                      入力
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-list-tab" data-toggle="pill" href="#custom-tabs-four-list" role="tab" aria-controls="custom-tabs-four-list" aria-selected="false">
                      一覧
                    </a>
                  </li>
                  <li class="nav-item ml-auto pr-2">
                    <a class="btn btn-block btn-default" href="{{route('time_card.create')}}">
                      <i class="fas fa-pen"></i>
                      作成
                    </a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-four-input" role="tabpanel" aria-labelledby="custom-tabs-four-input-tab">
                    {!! Form::open() !!}
                    <div class="row">
                      <div class="col-sm-2">
                        {!! Form::button('<i class="fas fa-play"></i>開始', $startOptions) !!}
                      </div>
                      <div class="col-sm-2">
                        {!! Form::button('<i class="fas fa-stop"></i>終了', $endOptions) !!}
                      </div>
                      <div class="col-sm-8">
                        {!! Form::select('category_id', $categories, optional($latestTimeCard)->category_id, $categoryIdOptions) !!}
                        <span class="error invalid-feedback">
                          @error('category_id') {{$message}} @enderror
                        </span>
                      </div>
                      <div class="col-sm-12">
                        {!! Form::textarea('memo', $latestTimeCard->memo ?? '', $memoOptions) !!}
                        <span class="error invalid-feedback">
                          @error('category_id') {{$message}} @enderror
                        </span>
                      </div>
                    </div>
                    {!! Form::close() !!}
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-list" role="tabpanel" aria-labelledby="custom-tabs-four-list-tab">
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
                              <button type="button" class="btn btn-default btn-modal" data-toggle="modal" data-target="#modal" data-daytime='{{$timeCard->date_and_time_format}}' data-link="{{route('time_card.destroy', ['time_card' => $timeCard->id])}}">
                                <i class="fas fa-trash"></i>
                                <div class="d-none modal-body">
                                  <p>{{sprintf('%s【開始】%s 【終了】%s', $timeCard->dateFormat, $timeCard->startTimeFormat, $timeCard->endTimeFormat)}}</p>
                                  <small>
                                    {{sprintf('%s', $timeCard->category->name)}}<br>
                                    {{sprintf('%s', $timeCard->memo)}}
                                  </small>
                                </div>
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
              <div class="card-footer">
                <h5>{{optional($latestTimeCard)->date_and_start_time_format}}</h5>
              </div>
            </div>
          </div>
        </div>
    </div>
    <x-adminlte-modal id="modal">
      <x-slot:icon>fas fa-trash text-danger</x-slot>
      <x-slot:title><span class="text-danger">削除の確認</span></x-slot>
      <x-slot:footerSlot>
        <x-adminlte-button class="btn btn-default" type="button" data-dismiss="modal" label="キャンセル"/>
        {{ Form::open(['method' => 'delete']) }}
          {!! Form::submit('削除する', ['class' => 'btn btn-primary modal-loading']) !!}
        {{ Form::close() }}
      </x-slot>
      <p>タイムカードデータを削除しますか？</p>
      <blockquote class="quote-danger">
        <div id="put-modal-body"></div>
      </blockquote>
    </x-adminlte-modal>
@stop

@section('js-for-page')
<script src="{{ mix('js/modal.js') }}?={{config('version.number')}}"></script>
<script src="{{ mix('js/datetimepicker.js') }}?={{config('version.number')}}"></script>
@stop

@php
  $startClass = 'bg-lightblue card-loading';
  $endClass = 'bg-maroon card-loading';
  if (isset($latestTimeCard->id) && is_null($latestTimeCard->end_time)) {
    $startClass .= ' disabled';
    $startTimeValue = false;
    $endTimeValue = true;
  } else {
    $endClass .= ' disabled';
    $startTimeValue = true;
    $endTimeValue = false;
  }
@endphp

@extends('layouts.app')

@section('title', 'AdminLTE|タイムカード')

@section('content-title', 'タイムカード')

@section('content')
  <div class="row">
    <div class="col-12" id="accordion">
      <div class="card card-gray-dark card-outline">
        <div class="card-header p-1">
          <ul class="nav nav-pills">
            <li class="nav-item p-2 pl-3">
              <a class="d-block" data-toggle="collapse" href="#collapseOne">
                <i class="fas fa-arrow-down"></i>
                入力
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
        <div id="collapseOne" class="collapse show" data-parent="#accordion">
          <div class="card-body">
            {!! Form::open() !!}
            <div class="row">
              <div class="col-sm-4">
                <x-adminlte-button :class="$startClass" type="submit" name='hasClieckedStart' formaction="{{route('time_card.start')}}" :value="$startTimeValue">
                  <x-slot:label>開始</x-slot>
                  <x-slot:icon>fas fa-play</x-slot>
                  <x-slot:theme>app</x-slot>
                </x-adminlte-button>
              </div>
              <div class="col-sm-4">
                <x-adminlte-button :class="$endClass" type="submit" name='hasClieckedEnd' formaction="{{route('time_card.end', ['time_card' => optional($latestTimeCard)->id])}}" :value="$endTimeValue">
                  <x-slot:label>終了</x-slot>
                  <x-slot:icon>fas fa-stop</x-slot>
                  <x-slot:theme>app</x-slot>
                </x-adminlte-button>
              </div>
              <div class="col-sm-8">
                <x-adminlte-select name="category_id">
                  <x-adminlte-options :options="$categorieList" :selected="old('category_id', optional($latestTimeCard)->category_id)">
                  </x-adminlte-options>
                </x-adminlte-select>
              </div>
              <div class="col-sm-12">
                <x-adminlte-text-editor name='memo' rows='10' placeholder="メモ">
                  {{old('memo', $latestTimeCard->memo ?? '')}}
                </x-adminlte-text-editor>
              </div>
            </div>
            {!! Form::close() !!}
          </div>
          <div class="card-footer">
            <h5>{{optional($latestTimeCard)->date_and_start_time_format}}</h5>
          </div>
        </div>
      </div>
      <div class="card card-gray-dark card-outline">
        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
          <div class="card-header">
            <h4 class="card-title w-100">一覧</h4>
          </div>
        </a>
        <div id="collapseTwo" class="collapse" data-parent="#accordion">
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
                      <a class="btn btn-default" href="{{route('time_card.show', ['time_card' => $timeCard->id])}}" title="詳細">
                        <i class="fas fa-file-alt"></i>
                      </a>
                      <a class="btn btn-default" href="{{route('time_card.edit', ['time_card' => $timeCard->id])}}" title="更新">
                        <i class="fas fa-edit"></i>
                      </a>
                      <x-adminlte-button class="btn-modal" type="button" data-toggle="modal" data-target="#modal{{$timeCard->id}}" title="削除">
                        <x-slot:icon>fas fa-trash</x-slot>
                      </x-adminlte-button>
                      <x-adminlte-modal id="modal{{$timeCard->id}}">
                        <x-slot:icon>fas fa-trash text-danger</x-slot>
                        <x-slot:title><span class="text-danger">削除の確認</span></x-slot>
                        <x-slot:footerSlot>
                          <x-adminlte-button class="btn btn-default" type="button" data-dismiss="modal" label="キャンセル"/>
                          {{ Form::open(['url' => route('time_card.destroy', ['time_card' => $timeCard->id]), 'method' => 'delete']) }}
                            {!! Form::submit('削除する', ['class' => 'btn btn-primary modal-loading']) !!}
                          {{ Form::close() }}
                        </x-slot>
                        <p>タイムカードデータを削除しますか？</p>
                        <blockquote class="quote-danger text-left">
                          <div id="put-modal-body">
                            <div id="put-modal-body">
                              <p>{{sprintf('%s【開始】%s 【終了】%s', $timeCard->dateFormat, $timeCard->startTimeFormat, $timeCard->endTimeFormat)}}</p>
                              <small>
                                {{sprintf('%s', $timeCard->category->name)}}<br>
                                {{sprintf('%s', $timeCard->memo)}}
                              </small>
                            </div>
                          </div>
                        </blockquote>
                      </x-adminlte-modal>
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

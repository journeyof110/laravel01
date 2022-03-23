@php
$openForm = [
  'method' => 'put',
  'route' => ['time_card.update', $timeCard->id ?? null]
];
@endphp

@extends('layouts.app')

@section('title', 'AdminLTE|タイムカードの更新')

@section('content-title', 'タイムカードの更新')

@section('content')
<div class="container-fluid">
  <div class="card card-olive card-outline card-default">
    {!! Form::open($openForm) !!}
    <div class="card-header">
      <h3 class="card-title">更新</h3>
    </div>
    <div class="card-body col-md-12">
      @include('time_card.components.form')
    </div>
    <div class="card-footer">
      {!! Form::submit('更新する', ['class' => 'btn btn-primary']) !!}
      <a href="{{route('time_card.index')}}" class="btn btn-secondary">戻る</a>
    </div>
    {!! Form::close() !!}
  </div>
</div>
@endsection

@section('js-for-page')
<script src="{{ mix('js/datetimepicker.js') }}?={{config('version.number')}}"></script>
@stop
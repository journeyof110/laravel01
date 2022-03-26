@php
$openForm = [
  'method' => 'post',
  'route' => ['time_card.store']
];
@endphp

@extends('layouts.app')

@section('title', 'AdminLTE|タイムカードの作成')

@section('content-title', 'タイムカードの作成')

@section('content')
<div class="card card-olive card-outline card-default">
  {!! Form::open($openForm) !!}
  <div class="card-header">
    <h3 class="card-title">作成</h3>
  </div>
  <div class="card-body col-md-12">
    @include('time_card.components.form')
  </div>
  <div class="card-footer">
    {!! Form::submit('作成する', ['class' => 'btn btn-primary card-loading']) !!}
    <a href="{{route('time_card.index')}}" class="btn btn-secondary">戻る</a>
  </div>
  {!! Form::close() !!}
</div>
@endsection

@section('js-for-page')
<script src="{{ mix('js/datetimepicker.js') }}?={{config('version.number')}}"></script>
@stop
@extends('adminlte::page')

@section('title', 'AdminLTE|タイムカードの作成')

@section('content_header')
  @include('components.alert')
  <h1 class="m-0 text-dark">タイムカードの作成</h1>
@stop

@section('content')
<div class="container-fluid">
  <div class="card card-default">
    {!! Form::open() !!}
    <div class="card-header">
      <h3 class="card-title">作成</h3>
    </div>
    <div class="card-body col-md-12">
      <div class="form-group row">
        <div class="col-md-2">
          <label>年月日</label>
        </div>
        <div class="col-md-3 input-group date" id="datetimepicker" data-target-input="nearest">
          <input type="text" name="date" value="{{old('date')}}" data-target="#datetimepicker" class="form-control {{(!$errors->has('date') ?: ' is-invalid')}}" placeholder="yyyy-mm-dd">
          <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
            <div class="input-group-text">
              <i class="far fa-calendar"></i>
            </div>
          </div>
          <span class="error invalid-feedback">
            @error('date') {{$message}} @enderror
          </span>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-2">
          <label>開始時刻</label>
        </div>
        <div class="col-md-3 input-group date input-time">
          <input type="time" name="start_time" value="{{old('start_time')}}" class="icon-del form-control {{(!$errors->has('start_time') ?: 'is-invalid')}}" placeholder="00:00">
          <div class="input-group-append">
            <div class="input-group-text">
              <i class="far fa-clock"></i>
            </div>
          </div>
          <span class="error invalid-feedback">
            @error('start_time') {{$message}} @enderror
          </span>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-2">
          <label>終了時刻</label>
        </div>
        <div class="col-md-3 input-group date input-time">
          <input type="time" name="end_time" value="{{old('end_time')}}" class="form-control {{(!$errors->has('end_time') ?: 'is-invalid')}}" placeholder="23:59">
          <div class="input-group-append">
            <div class="input-group-text">
              <i class="far fa-clock"></i>
            </div>
          </div>
          <span class="error invalid-feedback">
            @error('end_time') {{$message}} @enderror
          </span>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-2">
          <label>メモ</label>
        </div>
        <div class="col-md-10 input-group date">
          {!! Form::textarea('memo', old('memo'), ['class' => 'form-control ' . (!$errors->has('memo') ?: 'is-invalid'), 'rows' => 3]) !!}
          <span class="error invalid-feedback">
            @error('memo') {{$message}} @enderror
          </span>
        </div>
      </div>
    </div>
    <div class="card-footer">
      {!! Form::submit('作成する', ['class' => 'btn btn-primary']) !!}
      <a href="{{route('time_card')}}" class="btn btn-secondary">戻る</a>
    </div>
    {!! Form::close() !!}
  </div>
</div>
@endsection

@push('css')
<style type="text/css">
  .input-time{
    position: relative;
  }
  input[type="time"]::-webkit-calendar-picker-indicator{
    position: absolute;
    right: -40px;
    top: 0px;
    padding: 0;
    width: 40px;
    height: 36px;
    background: transparent;
    color: transparent;
    cursor: pointer;
  }
</style>
@endpush

@push('js')
<script type="text/javascript">
  $(function () {
    $('#datetimepicker').datetimepicker({
      format: 'YYYY-MM-DD',
      locale: 'ja',
    });
  });
</script>
@endpush
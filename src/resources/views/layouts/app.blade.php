@extends('adminlte::page')

@section('content_header')
  @include('components.toast')
  @include('components.loading')
  <h1 class="m-0 text-dark">@yield('content-title')</h1>
@stop

@section('js')
  <script src="{{ mix('js/toast.js') }}?={{config('version.number')}}"></script>
  <script src="{{ mix('js/loading.js') }}?={{config('version.number')}}"></script>
  @yield('js-for-page')
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
  @yield('css-for-page')
@endsection
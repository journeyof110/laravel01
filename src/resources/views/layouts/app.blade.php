@extends('adminlte::page')

@section('content_header')
  @include('components.toast')
  <h1 class="m-0 text-dark">@yield('content-title')</h1>
@stop

@section('js')
  <script src="{{ mix('js/toast.js') }}?={{config('version.number')}}"></script>
  @yield('js-for-page')
@stop

@section('css')
    @yield('css-for-page')
@endsection
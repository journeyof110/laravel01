@php
  switch (true) {
    case session('success'):
        $addClass = 'alert-success';
        $hidden = null;
        $icon = 'check';
        $header = '成功';
        $message = session('success');
      break;
    case session('error'):
        $addClass = 'alert-danger';
        $hidden = null;
        $icon = 'ban';
        $header = '失敗';
        $message = session('error');
      break;
    default:
        $addClass = null;
        $hidden = 'hidden';
        $icon = null;
        $header = null;
        $message = null;
      break;
  }
@endphp
  <div class="alert alert-dismissible fade show {{$addClass}}" {{$hidden}} role="alert">
    <h5>
      <i class="icon fas fa-{{$icon}}"></i>
        {{$header}}
    </h5>
    {{$message}}
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  </div>
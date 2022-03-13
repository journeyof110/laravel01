@php
  switch (true) {
    case session('success'):
        $id       = 'result-toast';
        $type     = 'success';
        $color    = '#28a745';
        $title    = '成功';
        $message  = session('success');
      break;
    case session('error'):
        $id       = 'result-toast';
        $type     = 'error';
        $color    = '#dc3545';
        $title    = '失敗';
        $message  = session('error');
      break;
    default:
        $id       = 'hidden';
        $type     = null;
        $color    = null;
        $title    = null;
        $message  = null;
      break;
  }
@endphp
  <template id="{{$id}}" data-background-color="{{$color}}">
    <swal-html>
        <h5>{{$title}}</h5>
        <p>{{$message}}</p>
    </swal-html>
    <swal-icon type="{{$type}}" color="white"></swal-icon>
    <swal-param name="allowEscapeKey" value="false" />
  </template>
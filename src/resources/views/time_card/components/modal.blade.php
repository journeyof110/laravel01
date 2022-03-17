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
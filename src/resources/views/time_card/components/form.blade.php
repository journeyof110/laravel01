<div class="form-group row">
  <div class="col-md-2">
    <label>年月日</label>
  </div>
  <div class="col-md-3 input-group date" id="datetimepicker" data-target-input="nearest">
    <input type="text" name="date" value="{{old('date', $timeCard->date ?? '')}}" data-target="#datetimepicker" class="form-control {{(!$errors->has('date') ?: ' is-invalid')}}" placeholder="yyyy-mm-dd">
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
    {!! Form::time('start_time',
      old('start_time', $timeCard->start_time ?? ''),
      [
        'class' => "form-control " . (!$errors->has('start_time') ?: 'is-invalid'),
        'placeholder' => "00:00"
      ]
    ) !!}
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
    {!! Form::time('end_time',
      old('end_time', $timeCard->end_time ?? ''),
      [
        'class' => "form-control " . (!$errors->has('end_time') ?: 'is-invalid'),
        'placeholder' => "23:59"
      ]
    ) !!}
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
    <label>カテゴリー</label>
  </div>
  <div class="col-md-10 input-group date">
    {!! Form::select('category_id',
      $categories,
      old('category_id', $timeCard->category->id ?? ''),
      ['class' => 'form-control ' . (!$errors->has('category_id') ?: 'is-invalid')]
    ) !!}
    <span class="error invalid-feedback">
      @error('category_id') {{$message}} @enderror
    </span>
  </div>
</div>
<div class="form-group row">
  <div class="col-md-2">
    <label>メモ</label>
  </div>
  <div class="col-md-10 input-group date">
    {!! Form::textarea('memo',
      old('memo', $timeCard->memo ?? ''),
      ['class' => 'form-control ' . (!$errors->has('memo') ?: 'is-invalid'), 'rows' => 10]
    ) !!}
    <span class="error invalid-feedback">
      @error('memo') {{$message}} @enderror
    </span>
  </div>
</div>

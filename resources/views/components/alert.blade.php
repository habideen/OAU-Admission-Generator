<div class="mb-4">
  @if (Session::get('status') == 'failed')
    <div class="alert alert-danger mb-3">{!! Session::get('message') !!}</div>
  @endif
  @if (Session::get('status') == 'success')
    <div class="alert alert-success mb-3">{!! Session::get('message') !!}</div>
  @endif
</div>

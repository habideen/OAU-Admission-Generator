<div class="mb-4">
  @if (Session::has('fail'))
    <div class="alert alert-danger mb-3">{!! Session::get('fail') !!}</div>
  @endif
  @if (Session::has('success'))
    <div class="alert alert-success mb-3">{!! Session::get('success') !!}</div>
  @endif
</div>

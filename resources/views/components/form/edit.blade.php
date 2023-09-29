@props(['action', 'name', 'value'])
<form action="/{{ account_type() . $action }}" method="post">
  @csrf
  @method('delete')
  <input type="hidden" name="{{ $name }}" value="{{ $value }}">
  <button type="submit" class="btn btn-danger waves-effect waves-light ps-2 pe-2" onclick="confirmDelete()"><i class="bx bx-trash"></i></button>
</form>

@props(['action', 'name', 'value', 'text'])
<form action="/{{ account_type() . $action }}" method="post" class="d-inline">
  @csrf
  @method('delete')
  <input type="hidden" name="{{ $name }}" value="{{ $value }}">
  <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="confirmDelete('{{ $text }}')"> <i
      class="bx bx-trash"></i> </button>
</form>

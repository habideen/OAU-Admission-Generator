@props([
    'isUpdate' => null,
    'updateText' => null,
    'defaultText' => null,
])

<button type="submit"
  class="btn btn-primary waves-effect waves-light ps-4 pe-4">{{ $isUpdate ? $updateText : $defaultText }}</button>

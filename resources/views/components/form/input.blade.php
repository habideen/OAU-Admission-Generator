@props(['name', 'label' => null, 'value' => null, 'parentClass' => null])

<div class="form-group {{ $parentClass }}">
  <label for="{{ $name }}">{{ $label }}</label>
  <input name="{{ $name }}" id="{{ $name }}" {!! $attributes->class(['form-control', $errors->has($name) ? 'is-invalid' : '']) !!} value="{{ $value }}">
  @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

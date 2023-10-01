@props(['name', 'label' => null, 'value' => null, 'parentClass' => null, 'bottomInfo' => null])

<div class="form-group {{ $parentClass }}">
  <label for="{{ $name }}">{{ $label }}</label>
  <input name="{{ $name }}" id="{{ $name }}" {!! $attributes->class(['form-control', $errors->has($name) ? 'is-invalid' : '']) !!}>
  <div class="text-muted">{!! $bottomInfo !!}</div>
  @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

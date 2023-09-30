@props(['name', 'label' => null, 'selected' => null, 'parentClass' => null, 'placeholder' => null, 'options' => [], 'optionsType', 'objKey' => null, 'objValue' => null])

<div class="form-group {{ $parentClass }}">
  <label for="{{ $name }}">{{ $label }}</label>
  <select name="{{ $name }}" id="{{ $name }}" {!! $attributes->class(['form-control', $errors->has($name) ? 'is-invalid' : '']) !!}>
    <option value="">{{ $placeholder }}</option>
    @if ($optionsType == 'array')
      @foreach ($options as $key)
        <option value="{{ $key }}" @selected($key == $selected)>{{ $key }}</option>
      @endforeach
    @elseif ($optionsType == 'assoc')
      @foreach ($options as $key => $value)
        <option value="{{ $key }}" @selected($key == $selected)>{{ $value }}</option>
      @endforeach
    @elseif($optionsType == 'object')
      @foreach ($options as $option)
        <option value="{{ $option->$objKey }}" @selected($option->$objKey == $selected)>{{ $option->$objValue }}</option>
      @endforeach
    @endif

  </select>
  @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

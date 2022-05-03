@php
    $hash = \Str::random(4);
@endphp

@props([
    'col' => 12,

    'label' => null,
    'id' => $id ? $id : $name,
    'name' => $hash,
    'placeholder' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'multiple' => false,
    'disabled' => false,
    'readonly' => false,
])

@php
    $selected = null;

    if (old($name)) {
        $selected = old($name);
    } elseif ($value) {
        $selected = $value;
    }
@endphp

<div class="col-sm-{{ $col }}">
    <div class="form-group">
        <label>
            {{ $label }}
            @if($required) <span class="text-red">*</span> @endif
        </label>

        <select
            class="form-control"
            id="{{ $id }}"
            name="{{ $name }}"
            @if ($required) required @endif
            @if ($multiple) multiple @endif
            @if ($readonly) readonly @endif
            @if ($disabled) disabled @endif>

                @if(!empty($placeholder))
                    <option value="">{{ $placeholder }}</option>
                @endif
                @foreach($options as $option)
                    @if(is_array($option))
                        <option
                            value="{{ $option['value'] }}"
                            {{ $selected == $option['value'] ? 'selected' : '' }}>{{ $option['text'] }}</option>
                    @elseif(is_object($option))
                        <option
                            value="{{ $option->value }}"
                            {{ $selected == $option->value ? 'selected' : '' }}>{{ $option->text }}</option>
                    @endif
                @endforeach
        </select>
    </div>
  </div>
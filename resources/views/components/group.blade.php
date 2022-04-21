@props([
    'col' => 12,
    'label' => null,
])

<div {{ $attributes->merge([ 'class' => 'form-group col-sm-'.$col ]) }}>
    @if ($label)
        <label>{{ $label }}</label>
    @endif

    {{ $slot }}
</div>
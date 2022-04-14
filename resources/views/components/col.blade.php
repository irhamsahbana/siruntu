@props([
    'col' => 12
])

<div {{ $attributes->merge([ 'class' => 'col-sm-'.$col ]) }}>
    {{ $slot }}
</div>
@props([
    'method' => 'GET',
    'action' => '#'
])

<form style="width: 100%" action="{{ $action }}" method="{{ $method }}">
    @csrf
    @if($method != 'GET')
        @method($method)
    @endif
    {{ $slot }}
</form>
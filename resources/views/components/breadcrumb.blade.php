@props([
    'list' => [],
])

@php
    // count the number of items in the list
    $count = count($list);
@endphp

<ol class="breadcrumb float-sm-right">
    @foreach($list as $index => $item)
        {{-- add class active when last item --}}
        <li class="breadcrumb-item {{ $index == $count - 1 ? 'active' : '' }}">
            @if(isset($item['href']))
                <a href="{{ $item['href'] }}">{{ $item['name'] }}</a>
            @else
                {{ $item['name'] }}
            @endif
        </li>
    @endforeach
</ol>
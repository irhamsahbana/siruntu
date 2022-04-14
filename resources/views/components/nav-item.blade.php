@props([
    'href' => '#',
    'icon' => 'fas fa-hashtag',
    'text' => '',
])

<li class="nav-item">
    <a href="{{ $href }}" class="nav-link">
        <i class="nav-icon {{ $icon }}"></i>
        <p class="text">{{ $text }}</p>
    </a>
</li>
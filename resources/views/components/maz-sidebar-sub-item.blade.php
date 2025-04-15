@props(['link', 'name', 'icon' => null])

<li class="submenu-item">
    <a href="{{ $link }}">
        @if($icon)
        <i class="{{ $icon }}"></i>
        @endif
        {{ $name }}
    </a>
</li>
@props(['icon', 'link', 'name'])

@php
use Illuminate\Support\Str;

$routeName = Request::route()->getName(); // ex: 'pages.jadwal-program'
$routeGroup = explode('.', $routeName)[1] ?? ''; // ex: 'jadwal-program'

$active = $routeGroup === Str::slug(strtolower($name)); // ex: 'jadwal-program' === 'jadwal-program'
$classes = $active ? 'sidebar-item active' : 'sidebar-item';
@endphp

<li class="{{ $classes }} {{ $slot->isEmpty() ? '' : 'has-sub' }}">
    <a href="{{ $slot->isEmpty() ? $link : '#' }}" class="sidebar-link">
        <i class="{{ $icon }}"></i>
        <span>{{ $name }}</span>
    </a>
    @if(!$slot->isEmpty())
        <ul class="submenu" style="display: {{ $active ? 'block' : 'none' }};">
            {{ $slot }}
        </ul>
    @endif
</li>

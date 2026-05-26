@php
    $brandColors = [
        '--primary' => $siteSettings['color_primary'] ?? null,
        '--accent' => $siteSettings['color_accent'] ?? null,
        '--dark' => $siteSettings['color_dark'] ?? null,
        '--light' => $siteSettings['color_light'] ?? null,
    ];
    $brandColors = array_filter($brandColors, fn ($v) => $v && preg_match('/^#[0-9a-fA-F]{6}$/', $v));
@endphp
@if(! empty($brandColors))
<style>
    :root {
        @foreach($brandColors as $name => $color)
            {{ $name }}: {{ $color }};
        @endforeach
    }
</style>
@endif

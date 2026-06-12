@php
    $srcset = $srcset ?? null;
    $src = $src ?? ($fallback ?? '');
    $imgSrc = $fallback ?? $src;
    $priority = $priority ?? false;
    $lazy = $lazy ?? ! $priority;
    $sizesAttr = $sizes ?? '100vw';
@endphp

@if($srcset)
    <picture>
        <source type="image/webp" srcset="{{ $srcset }}" sizes="{{ $sizesAttr }}">
        <img
            src="{{ $imgSrc }}"
            alt="{{ $alt }}"
            @if(! empty($width)) width="{{ $width }}" @endif
            @if(! empty($height)) height="{{ $height }}" @endif
            @if($priority) fetchpriority="high" @endif
            @if($lazy) loading="lazy" @endif
            decoding="async"
            @if(! empty($class)) class="{{ $class }}" @endif
        >
    </picture>
@else
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        @if(! empty($width)) width="{{ $width }}" @endif
        @if(! empty($height)) height="{{ $height }}" @endif
        @if($priority) fetchpriority="high" @endif
        @if($lazy) loading="lazy" @endif
        decoding="async"
        @if(! empty($class)) class="{{ $class }}" @endif
    >
@endif

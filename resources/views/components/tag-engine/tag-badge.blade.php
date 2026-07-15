@props(['variant' => 'neutral'])

@php
    $baseClasses = 'inline-flex items-center px-3 py-1.5 border-2 font-headline font-semibold text-sm uppercase';
    $variantClasses =
        $variant === 'magenta'
            ? 'bg-magenta border-on-magenta text-on-magenta'
            : 'bg-surface-muted border-border-strong text-text-heading';
@endphp

<span {{ $attributes->class([$baseClasses, $variantClasses]) }}>
    {{ $slot }}
</span>

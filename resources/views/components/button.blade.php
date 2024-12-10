@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors disabled:opacity-50';
    
    $variants = [
        'primary' => 'bg-nfl-primary text-white hover:bg-nfl-primary/90 focus:ring-nfl-primary/50',
        'secondary' => 'bg-nfl-secondary text-white hover:bg-nfl-secondary/90 focus:ring-nfl-secondary/50',
        'outline' => 'border border-nfl-primary text-nfl-primary hover:bg-nfl-primary hover:text-white focus:ring-nfl-primary/50',
        'ghost' => 'text-nfl-primary hover:bg-nfl-primary/10 focus:ring-nfl-primary/50'
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg'
    ];

    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<button 
    {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}
    @disabled($disabled)
>
    {{ $slot }}
</button>
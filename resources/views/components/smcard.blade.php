@props([
    'title' => null,
    'footer' => null,
    'padding' => true
])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg']) }}>
    @if($title)
        <div class="px-4 py-2 bg-nfl-primary border-b border-gray-200">
            <h3 class="text-sm font-semibold text-white">{{ $title }}</h3>
        </div>
    @endif
    
    <div @class(['px-6 py-6' => $padding])>
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif
</div>
@props([
    'title' => null,
    'description' => null,
    'actions' => null
])

<div {{ $attributes->merge(['class' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8']) }}>
    @if($title || $description || $actions)
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                @if($title)
                    <h2 class="text-2xl font-bold leading-7 text-nfl-primary sm:text-3xl sm:truncate">
                        {{ $title }}
                    </h2>
                @endif
                @if($description)
                    <p class="mt-1 text-gray-500">
                        {{ $description }}
                    </p>
                @endif
            </div>
            @if($actions)
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
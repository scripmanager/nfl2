@props(['player', 'game'])

<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 7a1 1 0 112 0v5a1 1 0 11-2 0V7zm1-3a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-yellow-700">
                {{ $player->name }} is locked (Game in progress)
                @if($game)
                    <span class="font-medium">
                        {{ $game->homeTeam->name }} vs {{ $game->awayTeam->name }}
                        ({{ $game->kickoff->format('M j, g:ia') }})
                    </span>
                @endif
            </p>
        </div>
    </div>
</div>
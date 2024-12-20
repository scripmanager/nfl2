<x-app-layout>
<x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Entries') }}
            </h2>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-nfl-background shadow-sm sm:rounded-lg">
                <div class="p-6 bg-nfl-background border-b border-gray-200">
                    @if($gamesStarted==0&&auth()->user()->entries()->count() < 4)
                        <a href="{{ route('entries.create') }}" class="btn-primary">
                            Create New Entry
                        </a>
                    @endif

                    <div class="mt-6 grid md:grid-cols-2 gap-4">
                        @foreach($entries as $entry)
                            <div class="p-4 mb-4 border rounded">
                                <h3 class="text-lg font-semibold">{{ $entry->entry_name }}</h3>
                                <p>Changes remaining: {{ $entry->getChangesRemaining() }}</p>
                                <div class="mt-2 inline-flex -space-x-px overflow-hidden rounded-md border bg-nfl-primary shadow-sm">
                                  <a href="{{ route('entries.roster', $entry) }}" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-nfl-secondndary focus:relative">
                                      View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions for') }} {{ $entry->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <p class="text-gray-600">Changes Remaining: {{ $entry->changes_remaining }}</p>
                    </div>

                    <div class="space-y-4">
                        @forelse($transactions as $transaction)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium">{{ $transaction->processed_at->format('M j, Y g:ia') }}</div>
                                        <div class="mt-2">
                                            <span class="text-red-600">Dropped: {{ $transaction->droppedPlayer->name }} ({{ $transaction->droppedPlayer->team->abbreviation }})</span>
                                            <br>
                                            <span class="text-green-600">Added: {{ $transaction->addedPlayer->name }} ({{ $transaction->addedPlayer->team->abbreviation }})</span>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-600">
                                            Position: {{ $transaction->roster_position }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                No transactions found for this entry.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
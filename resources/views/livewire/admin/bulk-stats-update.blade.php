
<div
    x-data
    x-on:open-modal.window="$wire.dispatch('showModal')"
    x-on:close-modal.window="$wire.dispatch('hideModal')"
>
    <form wire:submit="import" class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Bulk Update Player Stats</h2>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Stats File</label>
            <input type="file" wire:model="statsFile" class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-purple-50 file:text-purple-700
                hover:file:bg-purple-100
            "/>
            @error('statsFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        @if($previewData && count($previewData) > 0)
            <div class="mb-4">
                <h3 class="text-md font-medium text-gray-900 mb-2">Preview</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                @foreach(array_keys($previewData[0]) as $header)
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $header }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($previewData as $row)
                                <tr>
                                    @foreach($row as $value)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="mt-4 flex justify-end space-x-2">
            <button type="submit" 
                    class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50">
                <span wire:loading.remove wire:target="import">Import Stats</span>
                <span wire:loading wire:target="import">Processing...</span>
            </button>
            <button type="button" 
                    wire:click="$dispatch('close-modal', 'bulk-stats-update')"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Close
            </button>
        </div>

        <div wire:loading wire:target="import" class="mt-4 text-sm text-gray-600">
            Importing stats, please wait...
        </div>
    </form>

    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Example CSV Format -->
    <div class="p-4 mt-4 bg-gray-50 rounded">
        <h4 class="text-sm font-medium text-gray-900 mb-2">Expected CSV Format:</h4>
        <pre class="text-xs text-gray-600">
game_id,player_id,passing_yards,passing_tds,interceptions,rushing_yards,...
1,123,250,2,1,15,...
2,456,180,1,0,0,...
        </pre>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('stats-updated', () => {
                alert('Stats have been successfully imported!');
            });
        });
    </script>
</div>
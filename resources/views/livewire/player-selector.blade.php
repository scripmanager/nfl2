<div 
    x-data="{ 
        showDropdown: false,
        showDialog: false,
        dialogType: '',
        dialogMessage: ''
    }"
    x-init="
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showDialog', (data) => {
                showDialog = true;
                dialogType = data[0].type;
                dialogMessage = data[0].message;
            });
        });
    "
    class="relative"
>
    <button 
        @click="showDropdown = !showDropdown"
        class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
        Change Player
    </button>
    
    <!-- Dialog Modal -->
    <div>
    <div x-show="showDialog" 
         x-cloak
         @keydown.escape.window="showDialog = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true">
        <div @click="showDialog = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click.stop class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                     :class="dialogType === 'error' ? 'bg-red-100' : 'bg-green-100'">
                    <!-- Icon for error -->
                    <template x-if="dialogType === 'error'">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </template>
                    <!-- Icon for success -->
                    <template x-if="dialogType === 'success'">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </template>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" 
                        x-text="dialogType === 'error' ? 'Error' : 'Success'">
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500" x-text="dialogMessage"></p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                        @click="showDialog = false"
                        :class="dialogType === 'error' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Player Selection Dropdown -->
    <div 
    x-show="showDropdown" 
    @click.away="showDropdown = false"
    class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg z-[60] border"
    style="transform: translateX(-50%);">
        <div class="p-4">
            <form wire:submit.prevent="changePlayer">
                <select 
                    wire:model="selectedPlayerId" 
                    class="w-full rounded-md border-gray-300 text-sm mb-4"
                    required
                >
                    <option value="">Select a player</option>
                    @foreach($players as $player)
                        <option value="{{ $player->id }}">
                            {{ $player->name }} ({{ $player->team->name }})
                        </option>
                    @endforeach
                </select>
                
                <div class="flex justify-end space-x-2">
                    <button 
                        type="button"
                        @click="showDropdown = false"
                        class="px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                        Confirm Change
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
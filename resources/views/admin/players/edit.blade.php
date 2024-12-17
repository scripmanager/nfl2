<x-admin-layout>
   <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ __('Edit Player') }}
       </h2>
   </x-slot>

   <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
   @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
       <div class="p-6 bg-white border-b border-gray-200">
           <form method="POST" action="{{ route('admin.players.update', $player) }}">
               @csrf
               @method('PUT')
               <div class="mb-4">
                   <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                       Player Name
                   </label>
                   <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                          id="name" 
                          type="text" 
                          name="name" 
                          value="{{ old('name', $player->name) }}" 
                          required>
               </div>

               <div class="mb-4">
                   <label class="block text-gray-700 text-sm font-bold mb-2" for="team_id">
                       Team
                   </label>
                   <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="team_id"
                           name="team_id"
                           required>
                       <option value="">Select Team</option>
                       @foreach($teams as $team)
                           <option value="{{ $team->id }}" {{ old('team_id', $player->team_id) == $team->id ? 'selected' : '' }}>
                               {{ $team->name }}
                           </option>
                       @endforeach
                   </select>
               </div>

               <div class="mb-4">
                   <label class="block text-gray-700 text-sm font-bold mb-2" for="position">
                       Position
                   </label>
                   <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="position"
                           name="position"
                           required>
                       <option value="">Select Position</option>
                       <option value="QB" {{ old('position', $player->position) == 'QB' ? 'selected' : '' }}>QB</option>
                       <option value="RB" {{ old('position', $player->position) == 'RB' ? 'selected' : '' }}>RB</option>
                       <option value="WR" {{ old('position', $player->position) == 'WR' ? 'selected' : '' }}>WR</option>
                       <option value="TE" {{ old('position', $player->position) == 'TE' ? 'selected' : '' }}>TE</option>
                   </select>
               </div>

               <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                       Status
                   </label>
                   <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="status"
                           name="status"
                           required>
                       <option value="">Select Status</option>
                       <option value="active" {{ old('status', $player->status) == 'active' ? 'selected' : '' }}>Active</option>
                       <option value="inactive" {{ old('status', $player->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                       <option value="injured" {{ old('status', $player->status) == 'injured' ? 'selected' : '' }}>Injured</option>
                   </select>
               </div>

               <div class="mb-4">
                   <label class="flex items-center">
                       <input type="checkbox" 
                              name="is_active" 
                              value="1" 
                              {{ old('is_active', $player->is_active) ? 'checked' : '' }}
                              class="form-checkbox">
                       <span class="ml-2">Active</span>
                   </label>
               </div>

               <div class="flex items-center justify-end">
                   <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                       Update Player
                   </button>
               </div>
           </form>
       </div>
   </div>
</x-admin-layout>
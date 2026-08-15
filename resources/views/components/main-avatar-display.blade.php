<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }" class="flex flex-col items-center justify-center gap-3">
        <!-- Avatar Circle -->
        <button 
            type="button"
            x-on:click="document.getElementById('hidden-avatar-action-btn')?.click()"
            class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100 group transition-transform hover:scale-105 focus:outline-none"
        >
            <template x-if="state">
                <img :src="state.startsWith('http') ? state : '/storage/' + state" class="w-full h-full object-cover">
            </template>
            <template x-if="!state">
                <div class="w-full h-full flex items-center justify-center bg-primary-50 text-primary-500">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </template>
            
            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
        </button>

        <!-- Remove Button (Underneath) -->
        <template x-if="state">
            <button 
                type="button" 
                x-on:click="state = null"
                class="text-sm text-danger-600 hover:text-danger-500 font-medium px-3 py-1 rounded-full hover:bg-danger-50 transition-colors flex items-center gap-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Remover Avatar
            </button>
        </template>
    </div>
</x-dynamic-component>

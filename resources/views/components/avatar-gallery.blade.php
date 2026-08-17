<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php
        // Lista todos os arquivos da pasta avatars no disco public
        $files = \Illuminate\Support\Facades\Storage::disk('public')->files('avatars');
        // Filtra apenas imagens e inverte a ordem para as mais recentes (se houver) aparecerem primeiro
        $avatars = collect($files)
            ->filter(fn($file) => preg_match('/\.(png|jpg|jpeg|webp)$/i', $file))
            ->sortBy(function ($file) {
                $filename = strtolower(basename($file));
                if (str_starts_with($filename, '3d')) return 1;
                if (str_starts_with($filename, 'bluey') || str_starts_with($filename, 'notion')) return 3;
                return 2;
            })
            ->values();
    @endphp

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(107, 114, 128, 0.8);
        }
    </style>
    <div x-data="{ 
        state: $wire.$entangle('{{ $getStatePath() }}'),
        allAvatars: @js($avatars),
        limit: 100,
        get visibleAvatars() { return this.allAvatars.slice(0, this.limit); },
        loadMore() { this.limit += 50; }
    }">
        <div 
            style="min-height: 250px; max-height: 50vh; overflow-y: auto; overflow-x: hidden; display: grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap: 12px; justify-content: center; align-content: start;"
            class="p-2 sm:p-4 custom-scrollbar"
            @scroll="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 20) loadMore()"
        >
            <template x-for="avatar in visibleAvatars" :key="avatar">
                <button 
                    type="button" 
                    @click="state = avatar"
                    :style="state === avatar 
                        ? 'aspect-ratio: 1/1; width: 100%; max-width: 100px; border-style: solid; border-width: 4px; border-color: #f97316; border-color: var(--primary-600); border-color: rgb(var(--primary-600)); transform: scale(1.05); box-shadow: 0px 4px 10px rgba(0,0,0,0.2); transition: all 0.2s;' 
                        : 'aspect-ratio: 1/1; width: 100%; max-width: 100px; border: 1px solid #e5e7eb; transition: all 0.2s;'"
                    class="relative rounded-full overflow-hidden shrink-0 focus:outline-none mx-auto"
                >
                    <img :src="'/storage/' + avatar" alt="Avatar" class="w-full h-full object-cover">
                </button>
            </template>
        </div>
        
        <template x-if="allAvatars.length === 0">
            <div class="text-sm text-gray-500 text-center py-4">{{ __('filament-avatar-picker::avatar.no_avatars') }}</div>
        </template>
    </div>
</x-dynamic-component>

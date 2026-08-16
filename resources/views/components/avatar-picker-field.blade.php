<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php
        $state = $getState();
        $avatarUrl = $state ? \Illuminate\Support\Facades\Storage::disk('public')->url($state) : null;
    @endphp

    <div class="flex items-center space-x-4">
        <!-- Avatar Preview -->
        <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-center bg-gray-100 dark:bg-gray-800">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover" />
            @else
                <x-filament::icon
                    icon="heroicon-o-user"
                    class="w-12 h-12 text-gray-400 dark:text-gray-500"
                />
            @endif
        </div>

        <!-- Actions -->
        <div class="flex flex-col space-y-2">
            {{ $getAction('chooseAvatar') }}
            
            @if($state)
                {{ $getAction('removeAvatar') }}
            @endif
        </div>
    </div>
</x-dynamic-component>

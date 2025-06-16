<div class="flex justify-end gap-2">
    <x-filament::button
        color="gray"
        size="sm"
        wire:click="$dispatch('open-modal', { id: 'view-category-{{ $record->id }}' })"
    >
        View
    </x-filament::button>

    <x-filament::button
        color="primary"
        size="sm"
        tag="a"
        href="{{ route('filament.admin.resources.categories.edit', ['record' => $record->id]) }}"
    >
        Edit
    </x-filament::button>
</div>

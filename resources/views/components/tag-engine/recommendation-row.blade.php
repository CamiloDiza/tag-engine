@props(['item'])

<tr class="border-t-2 border-surface-muted">
    <td class="px-6 py-6 align-middle">
        <label class="relative inline-flex items-center justify-center w-4.5 h-4.5 cursor-pointer">
            <input type="checkbox" data-row-check
                class="peer appearance-none w-4.5 h-4.5 m-0 cursor-pointer bg-background border border-border-muted checked:bg-magenta checked:border-magenta">
            <svg class="pointer-events-none absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <path d="M5 13l4 4L19 7" />
            </svg>
        </label>
    </td>
    <td class="px-6 py-6 align-middle">
        <x-item-engine.item-badge :variant="$item['badge'] === 'magenta' ? 'magenta' : 'neutral'">
            {{ $item['hashtag'] }}
        </x-item-engine.item-badge>
    </td>
    <td class="px-6 py-6 align-middle">
        <x-item-engine.rating-stars :stars="$item['stars']" :label="$item['label']" />
    </td>
    <td class="px-6 py-6 align-middle font-bold text-text-heading">{{ $item['viewer_count'] }}</td>
    <td class="px-6 py-6 align-middle text-text-muted">{{ $item['rooms_count'] }}</td>
    <td class="px-6 py-6 align-middle font-bold uppercase">{{ }}</td>
    <td class="px-6 py-6 align-middle text-text-muted"></td>
    <td class="px-6 py-6 align-middle text-right">
        @if ($item['selected'])
            <button type="button" data-item-action
                class="inline-flex items-center justify-center gap-1 px-4 h-10.5 bg-magenta border-2 border-border-strong font-label text-[10px] uppercase text-on-magenta">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M5 13l4 4L19 7" />
                </svg>
                Selected
            </button>
        @else
            <button type="button" data-item-action
                class="inline-flex items-center justify-center px-3 h-6.75 bg-surface-muted border-2 border-border-strong font-label text-[10px] uppercase text-text-muted">
                + Add
            </button>
        @endif
    </td>
</tr>

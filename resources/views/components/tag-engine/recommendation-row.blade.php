@props(['tag'])

<tr data-tag-row data-tag="{{ $tag['name'] }}" class="border-t-2 border-surface-muted">
    <td class="px-6 py-6 align-middle">
        <label class="relative inline-flex items-center justify-center w-[18px] h-[18px] cursor-pointer">
            <input type="checkbox" data-row-check @checked($tag['selected'])
                class="peer appearance-none w-[18px] h-[18px] m-0 cursor-pointer bg-background border border-border-muted checked:bg-magenta checked:border-magenta">
            <svg class="pointer-events-none absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <path d="M5 13l4 4L19 7" />
            </svg>
        </label>
    </td>
    <td class="px-6 py-6 align-middle">
        <x-tag-engine.tag-badge :variant="$tag['badge'] === 'magenta' ? 'magenta' : 'neutral'">
            {{ $tag['name'] }}
        </x-tag-engine.tag-badge>
    </td>
    <td class="px-6 py-6 align-middle">
        <x-tag-engine.rating-stars :stars="$tag['stars']" :label="$tag['label']" />
    </td>
    <td class="px-6 py-6 align-middle font-bold text-text-heading">{{ $tag['viewers'] }}</td>
    <td class="px-6 py-6 align-middle text-text-muted">{{ $tag['rooms'] }}</td>
    <td class="px-6 py-6 align-middle font-bold uppercase {{ $tag['recClass'] }}">{{ $tag['rec'] }}</td>
    <td class="px-6 py-6 align-middle text-text-muted">{{ $tag['exp'] }}</td>
    <td class="px-6 py-6 align-middle text-right">
        @if ($tag['selected'])
            <button type="button" data-tag-action
                class="inline-flex items-center justify-center gap-1 px-4 h-[42px] bg-magenta border-2 border-border-strong font-label text-[10px] uppercase text-on-magenta">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M5 13l4 4L19 7" />
                </svg>
                Selected
            </button>
        @else
            <button type="button" data-tag-action
                class="inline-flex items-center justify-center px-3 h-[27px] bg-surface-muted border-2 border-border-strong font-label text-[10px] uppercase text-text-muted">
                + Add
            </button>
        @endif
    </td>
</tr>

<div>
    {{-- Table Header / Controls --}}
    <div class="flex flex-row justify-between items-center gap-6 p-6 bg-surface-raised border-b-2 border-border">
        <div class="flex flex-row items-center gap-6">
            <h2 class="font-label font-semibold text-xs uppercase tracking-[1.2px] text-text-heading">
                01. Live Ranking Explorer
            </h2>

            {{-- Search input --}}
            <div class="relative w-64">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-text-primary">
                    <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                    </svg>
                </span>
                <input type="search" wire:model.live="search" placeholder="Search tags..."
                    class="w-full h-9.5 pl-10 pr-4 py-2 bg-surface-input border border-border-muted font-body text-sm text-text-heading placeholder:text-border-muted focus:outline-none focus:border-text-primary">
            </div>
        </div>

        {{-- Results meta --}}
        <div class="flex flex-col items-end">
            <span
                class="font-label font-bold text-[10px] uppercase text-text-primary">{{ $snapshot->total_tags_captured }}
                Tags Available</span>
            <span class="font-label text-[10px] uppercase text-text-muted">Sorted by Best Recommendation</span>
        </div>
    </div>

    {{-- Table --}}
    <div class="w-full overflow-x-auto">
        <table data-tag-table class="w-full border-collapse text-left">
            <colgroup>
                <col class="w-16">
                <col class="w-36">
                <col class="w-48">
                <col class="w-24">
                <col class="w-20">
                <col class="w-44">
                <col>
                <col class="w-36">
            </colgroup>
            <thead class="bg-surface-muted border-b-2 border-border">
                <tr class="font-label font-bold text-xs uppercase tracking-[1.2px] text-text-primary">
                    <th class="px-6 py-5 font-bold align-middle">
                        <label class="relative inline-flex items-center justify-center w-4.5 h-4.5 cursor-pointer">
                            <input type="checkbox" data-check-all
                                class="peer appearance-none w-4.5 h-4.5 m-0 cursor-pointer bg-background border border-border-muted checked:bg-magenta checked:border-magenta">
                            <svg class="pointer-events-none absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </label>
                    </th>
                    <th class="px-6 py-5 font-bold">Tag</th>
                    <th class="px-6 py-5 font-bold">Rating</th>
                    <th class="px-6 py-5 font-bold">Viewers</th>
                    <th class="px-6 py-5 font-bold">Rooms</th>
                    <th class="px-6 py-5 font-bold">Recommendation</th>
                    <th class="px-6 py-5 font-bold">Explanation</th>
                    <th class="px-6 py-5 font-bold text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="font-body text-sm">
                @foreach ($items as $item)
                    <tr class="border-t-2 border-surface-muted">
                        <td></td>
                        <td class="px-6 py-6 align-middle">
                            <x-tag-engine.tag-badge>
                                {{ $item['hashtag'] }}
                            </x-tag-engine.tag-badge>
                        </td>
                        <td></td>
                        <td class="px-6 py-6 align-middle font-bold text-text-heading">{{ $item['viewer_count'] }}</td>
                        <td class="px-6 py-6 align-middle text-text-muted">{{ $item['room_count'] }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination footer --}}

    {{ $items->links('custom-pagination-links') }}
</div>

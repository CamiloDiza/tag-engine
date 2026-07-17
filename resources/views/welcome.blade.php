<x-layouts.app>
    <x-slot:title>Home - {{ config('app.name', 'Laravel') }}</x-slot:title>

    <x-app.header />

    <main class="px-6 py-12">
        <section>
            <header class="flex flex-row">
                <div class="basis-1/2">
                    <div>
                        <h1 class="font-headline text-text-heading text-5xl font-extrabold">TAG RECOMMENDATION ENGINE
                        </h1>
                    </div>
                    <div class="mt-4">
                        <p class="text-text-primary font-body">Find the best tags for your next live show. Data-driven
                            optimization
                            for creators and studios.
                        </p>
                    </div>
                </div>
                <div class="basis-1/2 flex justify-end items-center">
                    {{-- System Status card --}}
                    <div
                        class="relative bg-surface border-2 border-accent px-6 py-4 shadow-[8px_8px_0_0_var(--color-accent)]">
                        <p class="font-label font-bold text-[10px] uppercase tracking-[2px] text-text-primary">System
                            Status</p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="w-3 h-3 bg-accent"></span>
                            <span class="font-label font-bold text-sm uppercase tracking-[1px] text-text-heading">Engine
                                Live // 4.2ms Latency</span>
                        </div>
                    </div>
                </div>
            </header>
        </section>

        {{-- Section - Main Content Area: Priority Table --}}
        <section class="mt-12 flex flex-col w-full bg-surface border-2 border-border-strong">

            {{-- Table Header / Controls --}}
            <div
                class="flex flex-row justify-between items-center gap-6 p-6 bg-surface-raised border-b-2 border-border">
                <div class="flex flex-row items-center gap-6">
                    <h2 class="font-label font-semibold text-xs uppercase tracking-[1.2px] text-text-heading">
                        01. Live Ranking Explorer
                    </h2>

                    {{-- Search input --}}
                    <div class="relative w-64">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-text-primary">
                            <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="M21 21l-4.35-4.35"></path>
                            </svg>
                        </span>
                        <input type="search" placeholder="Search tags..."
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
                                <label
                                    class="relative inline-flex items-center justify-center w-4.5 h-4.5 cursor-pointer">
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
                                <td class="px-6 py-6 align-middle font-bold text-text-heading">
                                    {{ $item['viewer_count'] }}</td>
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
        </section>

        {{-- Section 02 - Refine Recommendations (collapsible) --}}
        <details open class="group mt-12 flex flex-col w-full bg-surface border-2 border-border-strong">
            <summary
                class="flex flex-row justify-between items-center p-6 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                <h2 class="font-label font-semibold text-xs uppercase tracking-[1.2px] text-text-heading">
                    02. Refine Recommendations
                </h2>
                <svg class="w-3 h-3 text-text-primary transition-transform duration-200 group-open:rotate-180"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M6 15l6-6 6 6"></path>
                </svg>
            </summary>

            <form class="flex flex-col gap-6 p-6 border-t-2 border-border">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- GENDER --}}
                    <div class="flex flex-col gap-3">
                        <label
                            class="font-label font-bold text-xs uppercase tracking-[1.2px] text-text-primary">Gender</label>
                        <div class="relative">
                            <select
                                class="w-full h-13 px-3 bg-surface-input border-2 border-border-strong font-body text-base text-text-heading appearance-none focus:outline-none">
                                <option>Female</option>
                                <option>Male</option>
                                <option>Trans</option>
                                <option>Couple</option>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 text-border-muted"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>
                    </div>

                    {{-- AGE --}}
                    <div class="flex flex-col gap-3">
                        <label
                            class="font-label font-bold text-xs uppercase tracking-[1.2px] text-text-primary">Age</label>
                        <input type="number" value="21"
                            class="w-full h-12.5 px-3 bg-surface-input border border-border-muted font-body text-base text-text-heading focus:outline-none focus:border-text-primary">
                    </div>

                    {{-- COUNTRY --}}
                    <div class="flex flex-col gap-3">
                        <label
                            class="font-label font-bold text-xs uppercase tracking-[1.2px] text-text-primary">Country</label>
                        <input type="text" value="Brazil"
                            class="w-full h-12.5 px-3 bg-surface-input border border-border-muted font-body text-base text-text-heading focus:outline-none focus:border-text-primary">
                    </div>

                    {{-- LANGUAGES --}}
                    <div class="flex flex-col gap-3">
                        <label
                            class="font-label font-bold text-xs uppercase tracking-[1.2px] text-text-primary">Languages</label>
                        <input type="text" value="Portuguese, English"
                            class="w-full h-12.5 px-3 bg-surface-input border border-border-muted font-body text-base text-text-heading focus:outline-none focus:border-text-primary">
                    </div>

                    {{-- HAIR COLOR --}}
                    <div class="flex flex-col gap-3">
                        <label class="font-label font-bold text-xs uppercase tracking-[1.2px] text-text-primary">Hair
                            Color</label>
                        <div class="relative">
                            <select
                                class="w-full h-13 px-3 bg-surface-input border-2 border-border-strong font-body text-base text-text-heading appearance-none focus:outline-none">
                                <option>Brunette</option>
                                <option>Blonde</option>
                                <option>Black</option>
                                <option>Redhead</option>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 text-border-muted"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>
                    </div>

                    {{-- BODY TYPE --}}
                    <div class="flex flex-col gap-3">
                        <label class="font-label font-bold text-xs uppercase tracking-[1.2px] text-text-primary">Body
                            Type</label>
                        <div class="relative">
                            <select
                                class="w-full h-13 px-3 bg-surface-input border-2 border-border-strong font-body text-base text-text-heading appearance-none focus:outline-none">
                                <option>Slim</option>
                                <option>Athletic</option>
                                <option>Average</option>
                                <option>Curvy</option>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-6 h-6 text-border-muted"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>
                    </div>

                    {{-- SKIN TONE --}}
                    <div class="flex flex-col gap-3">
                        <label class="font-label font-bold text-xs uppercase tracking-[1.2px] text-text-primary">Skin
                            Tone</label>
                        <input type="text" value="Tan"
                            class="w-full h-12.5 px-3 bg-surface-input border border-border-muted font-body text-base text-text-heading focus:outline-none focus:border-text-primary">
                    </div>

                    {{-- CURRENT ROOM SIZE --}}
                    <div class="flex flex-col gap-3">
                        <label
                            class="font-label font-bold text-xs uppercase tracking-[1.2px] text-text-primary">Current
                            Room Size</label>
                        <input type="number" value="120"
                            class="w-full h-12.5 px-3 bg-surface-input border border-border-muted font-body text-base text-text-heading focus:outline-none focus:border-text-primary">
                    </div>
                </div>

                {{-- Boolean filters --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <label
                        class="flex items-center gap-3 font-label font-bold text-xs uppercase tracking-[1.2px] text-text-heading cursor-pointer">
                        <input type="checkbox" checked
                            class="w-4 h-4 bg-background border border-border-muted accent-magenta"> Tattoos
                    </label>
                    <label
                        class="flex items-center gap-3 font-label font-bold text-xs uppercase tracking-[1.2px] text-text-heading cursor-pointer">
                        <input type="checkbox" checked
                            class="w-4 h-4 bg-background border border-border-muted accent-magenta"> Piercings
                    </label>
                    <label
                        class="flex items-center gap-3 font-label font-bold text-xs uppercase tracking-[1.2px] text-text-heading cursor-pointer">
                        <input type="checkbox"
                            class="w-4 h-4 bg-background border border-border-muted accent-magenta"> Lovense
                    </label>
                    <label
                        class="flex items-center gap-3 font-label font-bold text-xs uppercase tracking-[1.2px] text-text-heading cursor-pointer">
                        <input type="checkbox"
                            class="w-4 h-4 bg-background border border-border-muted accent-magenta"> Practices
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full h-15.5 bg-accent hover:bg-accent-hover transition-colors font-label font-medium text-xs uppercase tracking-[1.2px] text-background">
                    Update Recommendations
                </button>
            </form>
        </details>
    </main>

    {{-- Footer --}}
    <footer
        class="flex flex-row justify-between items-center px-6 py-6 border-t border-border font-label text-xs uppercase tracking-[1.2px] text-text-muted">
        <span>&copy; 2024 Tag Engine Brutalist Edition</span>
        <div class="flex flex-row items-center gap-6">
            <a href="" class="hover:text-text-primary">API Docs</a>
            <a href="" class="hover:text-text-primary">Privacy</a>
            <a href="" class="hover:text-text-primary">Terms</a>
        </div>
    </footer>

    {{-- Sticky selection bar --}}
    <div
        class="fixed bottom-0 inset-x-0 z-50 flex flex-row items-stretch justify-between bg-surface border-t-2 border-border-strong">
        <div class="flex flex-col justify-center gap-2 px-6 py-4">
            <div class="flex flex-row items-center gap-3">
                <span class="font-label font-bold text-[10px] uppercase tracking-[1.2px] text-text-muted">Selected
                    Tags</span>
                <span data-selected-badge
                    class="px-2 py-0.5 bg-magenta font-label font-bold text-[10px] uppercase text-on-magenta">0
                    selected</span>
            </div>
            <span data-selected-tags
                class="font-label font-bold text-[10px] uppercase tracking-[1.2px] text-text-heading">—</span>
        </div>

        <div class="flex flex-col justify-center items-center px-6 py-4">
            <span class="font-label text-[10px] uppercase tracking-[1.2px] text-text-muted">Est. Visibility
                Boost</span>
            <span class="font-headline font-extrabold text-3xl text-accent">+15.8%</span>
        </div>

        <button type="button" data-copy
            class="flex flex-row items-center justify-center gap-3 px-10 bg-accent hover:bg-accent-hover transition-colors font-label font-bold text-sm uppercase tracking-[1.2px] text-background disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="9" y="9" width="11" height="11"></rect>
                <path d="M5 15V5a2 2 0 012-2h10"></path>
            </svg>
            <span data-copy-label>Copy Selected Tags</span>
        </button>
    </div>
</x-layouts.app>

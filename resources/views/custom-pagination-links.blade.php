@php

if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
        (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <div class="flex flex-row justify-between items-center gap-6 p-6 bg-surface-raised border-t-2 border-border">

            <!-- Contenedor del Paginador Interactivo -->
            <div data-pagination class="flex flex-row items-center gap-1">

                {{-- Botón Anterior (Previous) --}}
                @if ($paginator->onFirstPage())
                    <span
                        class="px-3 py-2 h-8.75 bg-background border-2 border-border-strong font-label text-[10px] uppercase text-text-heading opacity-50 cursor-not-allowed flex items-center justify-center">
                        Previous
                    </span>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                        class="px-3 py-2 h-8.75 bg-background border-2 border-border-strong font-label text-[10px] uppercase text-text-heading transition hover:bg-gray-50 dark:hover:bg-gray-800">
                        Previous
                    </button>
                @endif

                {{-- Números de Página e Indicadores Intermedios (...) --}}
                <div class="flex flex-row items-center gap-1">
                    @foreach ($elements as $element)
                        {{-- Separador de Tres Puntos "..." --}}
                        @if (is_string($element))
                            <span class="px-1 font-body text-base text-text-muted">{{ $element }}</span>
                        @endif

                        {{-- Colección de Enlaces Numéricos --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <div wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                    @if ($page == $paginator->currentPage())
                                        {{-- Página Activa --}}
                                        <span
                                            class="w-8 h-8 border-2 border-magenta bg-magenta text-white font-label text-[10px] font-bold flex items-center justify-center cursor-default">
                                            {{ $page }}
                                        </span>
                                    @else
                                        {{-- Páginas Clickeables --}}
                                        <button type="button"
                                            wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                            class="w-8 h-8 border-2 border-border-strong font-label text-[10px] bg-background text-text-heading flex items-center justify-center transition hover:bg-gray-50 dark:hover:bg-gray-800">
                                            {{ $page }}
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>

                {{-- Botón Siguiente (Next) --}}
                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                        class="px-3 py-2 h-8.75 bg-background border-2 border-border-strong font-label text-[10px] uppercase text-text-heading transition hover:bg-gray-50 dark:hover:bg-gray-800">
                        Next
                    </button>
                @else
                    <span
                        class="px-3 py-2 h-8.75 bg-background border-2 border-border-strong font-label text-[10px] uppercase text-text-heading opacity-50 cursor-not-allowed flex items-center justify-center">
                        Next
                    </span>
                @endif
            </div>

            {{-- Showing --}}
            <span class="font-label font-bold text-[10px] uppercase text-text-muted">

                Showing

                {{ number_format($paginator->firstItem()) }}

                -

                {{ number_format($paginator->lastItem()) }}

                of

                {{ number_format($paginator->total()) }}

                Tags

            </span>

        </div>
    @endif
</div>

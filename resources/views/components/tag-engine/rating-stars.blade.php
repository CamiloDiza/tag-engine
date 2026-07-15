@props(['stars' => 0, 'label' => ''])

<div>
    <div class="flex items-center gap-1 text-text-primary">
        @for ($i = 0; $i < 5; $i++)
            <svg class="w-5 h-5 {{ $i >= $stars ? 'opacity-30' : '' }}" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l2.9 6.3 6.9.6-5.2 4.5 1.6 6.7L12 17l-6.2 3.6 1.6-6.7L2.2 8.9l6.9-.6z" />
            </svg>
        @endfor
    </div>

    <span class="mt-1.5 block font-label text-xs uppercase text-text-heading">{{ $label }}</span>
</div>

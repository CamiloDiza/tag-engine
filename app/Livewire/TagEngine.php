<?php

namespace App\Livewire;

use App\Models\ChaturbateSnapshot;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TagEngine extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $snapshot = ChaturbateSnapshot::latest()->first();
        $items = $snapshot ? $snapshot
            ->items()
            ->where('hashtag', 'like', "%{$this->search}%")
            ->paginate(15) : collect();

        return view('livewire.tag-engine', [
            'snapshot' => $snapshot,
            'items' => $items,
        ]);
    }
}

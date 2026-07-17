<?php

namespace App\Livewire;

use App\Models\ChaturbateSnapshot;
use Livewire\Component;
use Livewire\WithPagination;

class TagEngine extends Component
{
    use WithPagination;

    public function render()
    {
        $snapshot = ChaturbateSnapshot::latest()->first();
        $items = $snapshot ? $snapshot->items()->paginate(15) : collect();

        return view('livewire.tag-engine', [
            'snapshot' => $snapshot,
            'items' => $items,
        ]);
    }
}

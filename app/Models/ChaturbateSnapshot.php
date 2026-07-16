<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChaturbateSnapshot extends Model
{
    protected $fillable = [
        'total_tags_captured',
        'total_rooms',
        'total_viewers',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ChaturbateSnapshotItem::class, 'snapshot_id');
    }
}

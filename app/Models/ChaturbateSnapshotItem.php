<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChaturbateSnapshotItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'snapshot_id',
        'hashtag',
        'room_count',
        'viewer_count',
    ];

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(ChaturbateSnapshot::class, 'snapshot_id');
    }
}

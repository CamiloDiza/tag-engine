<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChaturbateSnapshotHashtag extends Model
{
    use HasFactory;

    protected $fillable = [
        'chaturbate_snapshot_id',
        'hashtag',
        'room_count',
        'viewer_count',
        'source_page',
        'position',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'room_count' => 'integer',
            'viewer_count' => 'integer',
            'source_page' => 'integer',
            'position' => 'integer',
            'payload' => 'array',
        ];
    }

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(ChaturbateSnapshot::class, 'chaturbate_snapshot_id');
    }
}

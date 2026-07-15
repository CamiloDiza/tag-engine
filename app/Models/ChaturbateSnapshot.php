<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChaturbateSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'captured_at',
        'source_url',
        'page_limit',
        'pages_fetched',
        'reported_total',
        'tag_count',
    ];

    protected function casts(): array
    {
        return [
            'captured_at' => 'immutable_datetime',
            'page_limit' => 'integer',
            'pages_fetched' => 'integer',
            'reported_total' => 'integer',
            'tag_count' => 'integer',
        ];
    }

    public function hashtags(): HasMany
    {
        return $this->hasMany(ChaturbateSnapshotHashtag::class);
    }

    public function scopeLatestCaptured(Builder $query): Builder
    {
        return $query->orderByDesc('captured_at');
    }
}

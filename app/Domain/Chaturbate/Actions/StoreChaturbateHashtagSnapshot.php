<?php

namespace App\Domain\Chaturbate\Actions;

use App\Domain\Chaturbate\Data\ChaturbateHashtagData;
use App\Domain\Chaturbate\Data\ChaturbateHashtagSnapshotData;
use App\Models\ChaturbateSnapshot;
use Illuminate\Support\Facades\DB;

final readonly class StoreChaturbateHashtagSnapshot
{
    public function handle(ChaturbateHashtagSnapshotData $snapshotData): ChaturbateSnapshot
    {
        return DB::transaction(function () use ($snapshotData): ChaturbateSnapshot {
            $snapshot = ChaturbateSnapshot::create([
                'captured_at' => $snapshotData->capturedAt,
                'source_url' => $snapshotData->sourceUrl,
                'page_limit' => $snapshotData->pageLimit,
                'pages_fetched' => $snapshotData->pagesFetched,
                'reported_total' => $snapshotData->reportedTotal,
                'tag_count' => $snapshotData->tagCount(),
            ]);

            $snapshot->hashtags()->createMany(
                $snapshotData->hashtags->map(
                    static fn (ChaturbateHashtagData $hashtag): array => $hashtag->toDatabaseAttributes(),
                )->all(),
            );

            return $snapshot->load('hashtags');
        });
    }
}

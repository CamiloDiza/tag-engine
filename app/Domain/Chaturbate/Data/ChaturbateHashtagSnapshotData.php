<?php

namespace App\Domain\Chaturbate\Data;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

final readonly class ChaturbateHashtagSnapshotData
{
    /**
     * @param  Collection<int, ChaturbateHashtagData>  $hashtags
     */
    public function __construct(
        public CarbonImmutable $capturedAt,
        public string $sourceUrl,
        public int $pageLimit,
        public int $pagesFetched,
        public ?int $reportedTotal,
        public Collection $hashtags,
    ) {}

    public function tagCount(): int
    {
        return $this->hashtags->count();
    }
}

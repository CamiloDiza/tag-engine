<?php

namespace App\Domain\Chaturbate\Actions;

use App\Domain\Chaturbate\Clients\ChaturbateClient;
use App\Domain\Chaturbate\Data\ChaturbateHashtagData;
use App\Domain\Chaturbate\Data\ChaturbateHashtagSnapshotData;
use App\Domain\Chaturbate\Exceptions\ChaturbateApiException;

final readonly class FetchChaturbateHashtagSnapshot
{
    public function __construct(
        private ChaturbateClient $client,
    ) {}

    public function handle(): ChaturbateHashtagSnapshotData
    {
        $page = 1;
        $pagesFetched = 0;
        $reportedTotal = null;
        $limit = null;
        $hashtagsByName = [];

        while (true) {
            $pageData = $this->client->fetchHashtagPage($page);
            $pagesFetched++;

            $limit ??= $pageData->limit;

            if ($reportedTotal === null && $pageData->reportedTotal !== null) {
                $reportedTotal = $pageData->reportedTotal;
            } elseif ($pageData->reportedTotal !== null && $reportedTotal !== null && $pageData->reportedTotal !== $reportedTotal) {
                throw ChaturbateApiException::invalidResponse(
                    operation: 'fetch_chaturbate_hashtag_snapshot',
                    url: $this->sourceUrl(),
                    page: $page,
                    message: 'The API reported different totals while the snapshot was being fetched.',
                    details: [
                        'previous_total' => $reportedTotal,
                        'current_total' => $pageData->reportedTotal,
                    ],
                );
            }

            foreach ($pageData->hashtags as $hashtag) {
                /** @var ChaturbateHashtagData $hashtag */
                if (! array_key_exists($hashtag->hashtag, $hashtagsByName)) {
                    $hashtagsByName[$hashtag->hashtag] = $hashtag;
                }
            }

            if (! $pageData->hasMorePages()) {
                break;
            }

            $page++;
        }

        return new ChaturbateHashtagSnapshotData(
            capturedAt: now(),
            sourceUrl: $this->sourceUrl(),
            pageLimit: $limit ?? 0,
            pagesFetched: $pagesFetched,
            reportedTotal: $reportedTotal,
            hashtags: collect(array_values($hashtagsByName)),
        );
    }

    private function sourceUrl(): string
    {
        return rtrim((string) config('services.chaturbate.base_url'), '/').'/'.ltrim((string) config('services.chaturbate.endpoint'), '/');
    }
}

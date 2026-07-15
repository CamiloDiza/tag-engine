<?php

namespace App\Domain\Chaturbate\Data;

use App\Domain\Chaturbate\Exceptions\ChaturbateApiException;
use Illuminate\Support\Collection;

final readonly class ChaturbateHashtagPageData
{
    /**
     * @param  Collection<int, ChaturbateHashtagData>  $hashtags
     */
    public function __construct(
        public int $page,
        public int $limit,
        public ?int $reportedTotal,
        public Collection $hashtags,
    ) {}

    public static function fromResponse(mixed $responseBody, int $page, int $limit, string $requestUrl): self
    {
        if (! is_array($responseBody)) {
            throw ChaturbateApiException::invalidResponse(
                operation: 'fetch_hashtag_page',
                url: $requestUrl,
                page: $page,
                message: 'The API response body is not a JSON object.',
            );
        }

        $hashtagsPayload = $responseBody['hashtags'] ?? null;

        if (! is_array($hashtagsPayload)) {
            throw ChaturbateApiException::invalidResponse(
                operation: 'fetch_hashtag_page',
                url: $requestUrl,
                page: $page,
                message: 'The API response is missing the hashtags array.',
            );
        }

        $reportedTotal = $responseBody['total'] ?? null;

        if ($reportedTotal !== null && ! self::isNonNegativeInteger($reportedTotal)) {
            throw ChaturbateApiException::invalidResponse(
                operation: 'fetch_hashtag_page',
                url: $requestUrl,
                page: $page,
                message: 'The API response contains an invalid total value.',
            );
        }

        $hashtags = collect();

        foreach ($hashtagsPayload as $position => $item) {
            if (! is_array($item)) {
                throw ChaturbateApiException::invalidResponse(
                    operation: 'fetch_hashtag_page',
                    url: $requestUrl,
                    page: $page,
                    message: 'The hashtags array contains an invalid entry.',
                    details: ['position' => $position],
                );
            }

            $hashtags->push(
                ChaturbateHashtagData::fromPayload(
                    payload: $item,
                    requestUrl: $requestUrl,
                    page: $page,
                    position: $position,
                ),
            );
        }

        return new self(
            page: $page,
            limit: $limit,
            reportedTotal: $reportedTotal === null ? null : (int) $reportedTotal,
            hashtags: $hashtags,
        );
    }

    public function hasMorePages(): bool
    {
        if ($this->reportedTotal !== null) {
            return $this->page < $this->totalPages();
        }

        return $this->hashtags->count() === $this->limit;
    }

    public function totalPages(): ?int
    {
        if ($this->reportedTotal === null) {
            return null;
        }

        return (int) ceil($this->reportedTotal / $this->limit);
    }

    private static function isNonNegativeInteger(mixed $value): bool
    {
        return is_int($value) && $value >= 0 || is_string($value) && is_numeric($value) && (int) $value >= 0;
    }
}

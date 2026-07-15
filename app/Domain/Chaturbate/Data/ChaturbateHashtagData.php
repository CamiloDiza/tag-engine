<?php

namespace App\Domain\Chaturbate\Data;

use App\Domain\Chaturbate\Exceptions\ChaturbateApiException;

final readonly class ChaturbateHashtagData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public string $hashtag,
        public int $roomCount,
        public int $viewerCount,
        public int $sourcePage,
        public int $position,
        public array $payload,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(array $payload, string $requestUrl, int $page, int $position): self
    {
        $hashtag = $payload['hashtag'] ?? null;

        if (! is_string($hashtag) || $hashtag === '') {
            throw ChaturbateApiException::invalidResponse(
                operation: 'fetch_hashtag_page',
                url: $requestUrl,
                page: $page,
                message: 'The hashtag entry is missing a valid hashtag value.',
                details: ['position' => $position],
            );
        }

        $roomCount = $payload['room_count'] ?? null;
        $viewerCount = $payload['viewer_count'] ?? null;

        if (! self::isNonNegativeInteger($roomCount) || ! self::isNonNegativeInteger($viewerCount)) {
            throw ChaturbateApiException::invalidResponse(
                operation: 'fetch_hashtag_page',
                url: $requestUrl,
                page: $page,
                message: 'The hashtag entry contains invalid counter values.',
                details: [
                    'position' => $position,
                    'hashtag' => $hashtag,
                ],
            );
        }

        return new self(
            hashtag: $hashtag,
            roomCount: (int) $roomCount,
            viewerCount: (int) $viewerCount,
            sourcePage: $page,
            position: $position,
            payload: $payload,
        );
    }

    public function toDatabaseAttributes(): array
    {
        return [
            'hashtag' => $this->hashtag,
            'room_count' => $this->roomCount,
            'viewer_count' => $this->viewerCount,
            'source_page' => $this->sourcePage,
            'position' => $this->position,
            'payload' => $this->payload,
        ];
    }

    private static function isNonNegativeInteger(mixed $value): bool
    {
        return is_int($value) && $value >= 0 || is_string($value) && is_numeric($value) && (int) $value >= 0;
    }
}

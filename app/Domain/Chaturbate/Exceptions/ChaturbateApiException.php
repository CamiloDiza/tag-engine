<?php

namespace App\Domain\Chaturbate\Exceptions;

use RuntimeException;
use Throwable;

final class ChaturbateApiException extends RuntimeException
{
    /**
     * @param  array<string, mixed>|null  $details
     */
    public function __construct(
        string $message,
        public readonly string $operation,
        public readonly string $url,
        public readonly ?int $page = null,
        public readonly ?int $statusCode = null,
        public readonly ?string $responseBodySnippet = null,
        public readonly ?array $details = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public static function connectionFailed(string $operation, string $url, int $page, Throwable $previous): self
    {
        return new self(
            message: 'The Chaturbate API request could not be completed.',
            operation: $operation,
            url: $url,
            page: $page,
            previous: $previous,
        );
    }

    public static function unexpectedStatus(string $operation, string $url, int $page, int $statusCode, string $responseBody): self
    {
        return new self(
            message: 'The Chaturbate API returned an unexpected HTTP status.',
            operation: $operation,
            url: $url,
            page: $page,
            statusCode: $statusCode,
            responseBodySnippet: self::limitBody($responseBody),
        );
    }

    /**
     * @param  array<string, mixed>|null  $details
     */
    public static function invalidResponse(
        string $operation,
        string $url,
        int $page,
        string $message,
        ?array $details = null,
        ?Throwable $previous = null,
    ): self {
        return new self(
            message: $message,
            operation: $operation,
            url: $url,
            page: $page,
            details: $details,
            previous: $previous,
        );
    }

    public function context(): array
    {
        return array_filter([
            'operation' => $this->operation,
            'url' => $this->url,
            'page' => $this->page,
            'status_code' => $this->statusCode,
            'response_body_snippet' => $this->responseBodySnippet,
            'details' => $this->details,
        ], static fn (mixed $value): bool => $value !== null);
    }

    private static function limitBody(string $responseBody): string
    {
        return mb_strimwidth(trim($responseBody), 0, 1000, '...');
    }
}

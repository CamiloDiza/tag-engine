<?php

namespace App\Domain\Chaturbate\Clients;

use App\Domain\Chaturbate\Data\ChaturbateHashtagPageData;
use App\Domain\Chaturbate\Exceptions\ChaturbateApiException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Throwable;

final readonly class HttpChaturbateClient implements ChaturbateClient
{
    public function __construct(
        private string $baseUrl,
        private string $endpoint,
        private int $limit,
        private int $timeout,
        private int $connectTimeout,
        private int $retryAttempts,
        private int $retrySleepMilliseconds,
    ) {}

    public function fetchHashtagPage(int $page): ChaturbateHashtagPageData
    {
        $requestUrl = rtrim($this->baseUrl, '/').'/'.ltrim($this->endpoint, '/');

        try {
            $response = $this->request()->get($requestUrl, $this->queryParameters($page));
        } catch (ConnectionException $exception) {
            throw ChaturbateApiException::connectionFailed(
                operation: 'fetch_hashtag_page',
                url: $requestUrl,
                page: $page,
                previous: $exception,
            );
        } catch (RequestException $exception) {
            throw ChaturbateApiException::unexpectedStatus(
                operation: 'fetch_hashtag_page',
                url: $requestUrl,
                page: $page,
                statusCode: $exception->response->status(),
                responseBody: $exception->response->body(),
            );
        }

        return ChaturbateHashtagPageData::fromResponse(
            responseBody: $response->json(),
            page: $page,
            limit: $this->limit,
            requestUrl: $requestUrl,
        );
    }

    /**
     * @return array{sort: string, page: int, g: string, limit: int}
     */
    private function queryParameters(int $page): array
    {
        return [
            'sort' => '',
            'page' => $page,
            'g' => '',
            'limit' => $this->limit,
        ];
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl(rtrim($this->baseUrl, '/'))
            ->acceptJson()
            ->timeout($this->timeout)
            ->connectTimeout($this->connectTimeout)
            ->retry($this->retryDelays(), function (Throwable $exception): bool {
                return $exception instanceof ConnectionException
                    || ($exception instanceof RequestException && (
                        $exception->response->serverError() || $exception->response->tooManyRequests()
                    ));
            });
    }

    /**
     * @return array<int, int>
     */
    private function retryDelays(): array
    {
        $delays = [];
        $delay = $this->retrySleepMilliseconds;

        for ($attempt = 1; $attempt < $this->retryAttempts; $attempt++) {
            $delays[] = $delay;
            $delay *= 2;
        }

        return $delays;
    }
}

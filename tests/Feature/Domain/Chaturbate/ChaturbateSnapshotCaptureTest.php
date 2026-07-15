<?php

use App\Domain\Chaturbate\Actions\CaptureChaturbateHashtagSnapshot;
use App\Domain\Chaturbate\Exceptions\ChaturbateApiException;
use App\Models\ChaturbateSnapshot;
use App\Models\ChaturbateSnapshotHashtag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('captures and stores a complete snapshot history', function (): void {
    config(['services.chaturbate.limit' => 2]);

    Http::preventStrayRequests();
    Http::fake(function (Request $request): mixed {
        preg_match('/[?&]page=(\d+)/', $request->url(), $matches);
        $page = (int) ($matches[1] ?? 0);

        return match ($page) {
            1 => Http::response([
                'hashtags' => [
                    [
                        'hashtag' => 'alpha',
                        'room_count' => 10,
                        'viewer_count' => 100,
                        'top_rooms' => [[
                            'room' => 'alpha-room',
                            'viewers' => 50,
                        ]],
                        'unexpected_field' => 'kept',
                    ],
                    [
                        'hashtag' => 'beta',
                        'room_count' => 20,
                        'viewer_count' => 200,
                        'top_rooms' => [],
                    ],
                ],
                'total' => 3,
            ]),
            2 => Http::response([
                'hashtags' => [
                    [
                        'hashtag' => 'beta',
                        'room_count' => 21,
                        'viewer_count' => 201,
                        'top_rooms' => [],
                    ],
                    [
                        'hashtag' => 'gamma',
                        'room_count' => 30,
                        'viewer_count' => 300,
                        'top_rooms' => [],
                    ],
                ],
                'total' => 3,
            ]),
            default => Http::response(['hashtags' => [], 'total' => 3]),
        };
    });

    $snapshot = app(CaptureChaturbateHashtagSnapshot::class)->handle();

    expect($snapshot)
        ->toBeInstanceOf(ChaturbateSnapshot::class)
        ->and($snapshot->captured_at)->not->toBeNull()
        ->and($snapshot->source_url)->toBe('https://chaturbate.com/api/ts/hashtags/tag-table-data/')
        ->and($snapshot->page_limit)->toBe(2)
        ->and($snapshot->pages_fetched)->toBe(2)
        ->and($snapshot->reported_total)->toBe(3)
        ->and($snapshot->tag_count)->toBe(3)
        ->and($snapshot->hashtags->pluck('hashtag')->all())->toBe(['alpha', 'beta', 'gamma'])
        ->and($snapshot->hashtags->firstWhere('hashtag', 'beta')->room_count)->toBe(20)
        ->and($snapshot->hashtags->firstWhere('hashtag', 'gamma')->viewer_count)->toBe(300)
        ->and($snapshot->hashtags->firstWhere('hashtag', 'alpha')->payload)->toMatchArray([
            'top_rooms' => [[
                'room' => 'alpha-room',
                'viewers' => 50,
            ]],
            'unexpected_field' => 'kept',
        ]);

    expect($snapshot->exists)->toBeTrue();
    expect(ChaturbateSnapshot::all()->count())->toBe(1);
    expect(ChaturbateSnapshotHashtag::all()->count())->toBe(3);
    Http::assertSentCount(2);
});

test('retries rate limited requests and eventually succeeds', function (): void {
    config(['services.chaturbate.limit' => 2]);

    Http::preventStrayRequests();

    $attempts = 0;

    Http::fake(function () use (&$attempts): mixed {
        $attempts++;

        return $attempts === 1
            ? Http::response(['message' => 'Too Many Requests'], 429)
            : Http::response([
                'hashtags' => [
                    [
                        'hashtag' => 'alpha',
                        'room_count' => 10,
                        'viewer_count' => 100,
                        'top_rooms' => [],
                    ],
                ],
                'total' => 1,
            ]);
    });

    $snapshot = app(CaptureChaturbateHashtagSnapshot::class)->handle();

    expect($snapshot->tag_count)->toBe(1)
        ->and($snapshot->hashtags->first()->hashtag)->toBe('alpha');

    Http::assertSentCount(2);
});

test('fails when the api response structure is invalid', function (): void {
    config(['services.chaturbate.limit' => 2]);

    Http::preventStrayRequests();
    Http::fake([
        '*' => Http::response([
            'total' => 1,
        ]),
    ]);

    expect(fn () => app(CaptureChaturbateHashtagSnapshot::class)->handle())
        ->toThrow(ChaturbateApiException::class, 'The API response is missing the hashtags array.');
});

test('fails when the api request cannot be completed', function (): void {
    config(['services.chaturbate.limit' => 2]);

    Http::preventStrayRequests();
    Http::fake([
        '*' => Http::failedConnection(),
    ]);

    expect(fn () => app(CaptureChaturbateHashtagSnapshot::class)->handle())
        ->toThrow(ChaturbateApiException::class, 'The Chaturbate API request could not be completed.');
});

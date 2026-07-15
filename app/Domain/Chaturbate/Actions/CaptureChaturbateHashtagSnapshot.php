<?php

namespace App\Domain\Chaturbate\Actions;

use App\Models\ChaturbateSnapshot;

final readonly class CaptureChaturbateHashtagSnapshot
{
    public function __construct(
        private FetchChaturbateHashtagSnapshot $fetchSnapshot,
        private StoreChaturbateHashtagSnapshot $storeSnapshot,
    ) {}

    public function handle(): ChaturbateSnapshot
    {
        return $this->storeSnapshot->handle(
            $this->fetchSnapshot->handle(),
        );
    }
}

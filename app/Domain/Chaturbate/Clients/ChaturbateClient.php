<?php

namespace App\Domain\Chaturbate\Clients;

use App\Domain\Chaturbate\Data\ChaturbateHashtagPageData;

interface ChaturbateClient
{
    public function fetchHashtagPage(int $page): ChaturbateHashtagPageData;
}

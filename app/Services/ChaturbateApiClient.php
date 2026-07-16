<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChaturbateApiClient
{
    protected string $url = 'https://chaturbate.com/api/ts/hashtags/tag-table-data/';

    public function fetchAllHashtags(): array
    {
        $allHashtags = [];
        $page = 1;
        $limit = 100;
        $hasMorePages = true;

        try {
            while ($hasMorePages) {
                $response = Http::timeout(30)
                    ->retry(3, 2000)
                    ->get($this->url, [
                        'limit' => $limit,
                        'page' => $page,
                    ]);

                if ($response->failed()) {
                    Log::error("Error consultando Chaturbate API en página {$page}: Status ".$response->status());
                    break;
                }

                $data = $response->json();
                $hashtags = $data['hashtags'] ?? [];

                if (empty($hashtags)) {
                    $hasMorePages = false;
                    break;
                }

                $allHashtags = array_merge($allHashtags, $hashtags);

                if (count($hashtags) < $limit) {
                    $hasMorePages = false;
                } else {
                    $page++;
                }

                usleep(100000);
            }

            return $allHashtags;

        } catch (\Exception $e) {
            Log::error('Excepción al consultar Chaturbate API: '.$e->getMessage());

            return $allHashtags;
        }
    }
}

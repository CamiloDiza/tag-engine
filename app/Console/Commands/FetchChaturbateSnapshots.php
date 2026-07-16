<?php

namespace App\Console\Commands;

use App\Models\ChaturbateSnapshot;
use App\Services\ChaturbateApiClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('chaturbate:fetch-snapshot')]
#[Description('Command description')]
class FetchChaturbateSnapshots extends Command
{
    protected $signature = 'chaturbate:fetch-snapshot';

    protected $description = 'Consume la API de Chaturbate y guarda un snapshot inmutable localmente';

    public function handle(ChaturbateApiClient $service): int
    {
        $this->info('Fetching Chaturbate snapshots...');

        $tags = $service->fetchAllHashtags();

        if (empty($tags)) {
            $this->error('No se pudieron obtener hashtags de Chaturbate.');

            return Command::FAILURE;
        }

        $totalTags = count($tags);
        $this->info("Se han obtenido {$totalTags} hashtags de Chaturbate.");

        DB::transaction(function () use ($tags, $totalTags) {

            $totalRooms = 0;
            $totalViewers = 0;
            $itemsToInsert = [];

            $snapshot = ChaturbateSnapshot::create([
                'total_tags_captured' => $totalTags,
                'total_rooms' => 0,
                'total_viewers' => 0,
            ]);

            foreach ($tags as $tag) {
                $roomCount = (int) ($tag['room_count'] ?? 0);
                $viewerCount = (int) ($tag['viewer_count'] ?? 0);

                $totalRooms += $roomCount;
                $totalViewers += $viewerCount;

                $itemsToInsert[] = [
                    'snapshot_id' => $snapshot->id,
                    'hashtag' => $tag['hashtag'],
                    'room_count' => $roomCount,
                    'viewer_count' => $viewerCount,
                ];
            }

            $chunks = array_chunk($itemsToInsert, 500);
            foreach ($chunks as $chunk) {
                $snapshot->items()->createMany($chunk);
            }

            $snapshot->update([
                'total_rooms' => $totalRooms,
                'total_viewers' => $totalViewers,
            ]);
        });

        $this->info('¡Snapshot guardado exitosamente!');

        return Command::SUCCESS;
    }
}

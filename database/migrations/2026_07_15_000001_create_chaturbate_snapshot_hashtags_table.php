<?php

use App\Models\ChaturbateSnapshot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chaturbate_snapshot_hashtags', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(ChaturbateSnapshot::class)->constrained()->cascadeOnDelete();
            $table->string('hashtag')->index();
            $table->unsignedInteger('room_count');
            $table->unsignedInteger('viewer_count');
            $table->unsignedInteger('source_page')->index();
            $table->unsignedInteger('position');
            $table->json('payload');
            $table->timestamps();

            $table->unique(['chaturbate_snapshot_id', 'hashtag']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chaturbate_snapshot_hashtags');
    }
};

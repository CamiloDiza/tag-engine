<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chaturbate_snapshots', function (Blueprint $table) {
            $table->id();
            $table->integer('total_tags_captured')->default(0);
            $table->integer('total_rooms')->default(0);
            $table->integer('total_viewers')->default(0);
            $table->timestamps();
        });

        Schema::create('chaturbate_snapshot_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('snapshot_id')
                ->constrained('chaturbate_snapshots')
                ->cascadeOnDelete();

            $table->string('hashtag')->index(); // Indexamos para búsquedas rápidas
            $table->integer('room_count');
            $table->integer('viewer_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chaturbate_snapshot_items');
        Schema::dropIfExists('chaturbate_snapshots');
    }
};

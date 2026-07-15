<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chaturbate_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->timestamp('captured_at')->index();
            $table->string('source_url');
            $table->unsignedInteger('page_limit');
            $table->unsignedInteger('pages_fetched');
            $table->unsignedInteger('reported_total')->nullable();
            $table->unsignedInteger('tag_count');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chaturbate_snapshots');
    }
};

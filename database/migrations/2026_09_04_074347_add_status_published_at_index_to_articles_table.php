<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The public listing filters `status = 'published'` and
     * `published_at <= now()` ordered by `published_at` descending. A single
     * composite index serves that query; the standalone `status` index it
     * replaces becomes redundant because `status` is the composite's prefix.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->index(['status', 'published_at']);
            $table->dropIndex(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->index(['status']);
            $table->dropIndex(['status', 'published_at']);
        });
    }
};

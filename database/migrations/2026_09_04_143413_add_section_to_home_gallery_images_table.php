<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * One gallery now feeds two spots on the home page: the "Work gains
     * value" editorial grid and the Front Covers slider.
     */
    public function up(): void
    {
        Schema::table('home_gallery_images', function (Blueprint $table) {
            $table->string('section', 16)->default('statement')->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_gallery_images', function (Blueprint $table) {
            $table->dropColumn('section');
        });
    }
};

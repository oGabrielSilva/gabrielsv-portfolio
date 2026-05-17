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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500)->index();
            $table->string('route_name', 200)->nullable()->index();
            $table->string('referrer_host', 255)->nullable()->index();
            $table->string('device', 20)->nullable(); // mobile, tablet, desktop
            $table->boolean('is_bot')->default(false)->index();
            $table->string('country', 2)->nullable(); // ISO-3166-1 alpha-2
            $table->string('utm_source', 100)->nullable()->index();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable()->index();
            $table->string('utm_content', 100)->nullable();
            $table->string('utm_term', 100)->nullable();
            $table->timestamp('viewed_at')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};

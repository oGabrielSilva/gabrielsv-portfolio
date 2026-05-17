<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedSmallInteger('reading_time')->default(1)->after('body_json');
            $table->enum('kind', ['essay', 'note', 'craft'])->default('essay')->after('reading_time')->index();
            $table->boolean('featured')->default(false)->after('kind')->index();
            $table->string('series_slug', 100)->nullable()->after('featured')->index();
            $table->unsignedSmallInteger('series_order')->nullable()->after('series_slug');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['reading_time', 'kind', 'featured', 'series_slug', 'series_order']);
        });
    }
};

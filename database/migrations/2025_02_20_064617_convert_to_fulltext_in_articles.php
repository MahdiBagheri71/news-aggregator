<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', static function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText(['title', 'description', 'content'], 'articles_search_fulltext');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', static function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropFullText('articles_search_fulltext');
            }
        });
    }
};

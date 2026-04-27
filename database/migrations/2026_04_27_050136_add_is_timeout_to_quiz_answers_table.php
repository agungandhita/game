<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_answers', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_answers', 'is_timeout')) {
                $table->boolean('is_timeout')->default(false)->after('is_correct');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quiz_answers', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_answers', 'is_timeout')) {
                $table->dropColumn('is_timeout');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('level_results', function (Blueprint $table) {
            if (!Schema::hasColumn('level_results', 'total_timeout')) {
                $table->integer('total_timeout')->default(0)->after('total_questions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('level_results', function (Blueprint $table) {
            if (Schema::hasColumn('level_results', 'total_timeout')) {
                $table->dropColumn('total_timeout');
            }
        });
    }
};

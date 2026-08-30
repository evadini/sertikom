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
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('date', 'date_start');
            $table->date('date_end')->nullable()->after('date_start');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('date_start', 'date');
            $table->dropColumn('date_end');
        });
    }
};

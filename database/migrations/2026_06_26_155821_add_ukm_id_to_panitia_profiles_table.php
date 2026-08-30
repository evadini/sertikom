<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panitia_profiles', function (Blueprint $table) {
            $table->dropColumn('asal_ukm');
            $table->foreignId('ukm_id')->after('user_id')->constrained('ukms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('panitia_profiles', function (Blueprint $table) {
            $table->dropForeign(['ukm_id']);
            $table->dropColumn('ukm_id');
            $table->string('asal_ukm')->after('user_id');
        });
    }
};

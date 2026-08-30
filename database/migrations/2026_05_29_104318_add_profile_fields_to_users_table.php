<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu kolom mana yang belum ada
            if (!Schema::hasColumn('users', 'nim')) {
                $table->string('nim')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'no_telpon')) {
                $table->string('no_telpon')->nullable()->after('nim');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nim')) {
                $table->dropColumn('nim');
            }
            if (Schema::hasColumn('users', 'no_telpon')) {
                $table->dropColumn('no_telpon');
            }
        });
    }
};

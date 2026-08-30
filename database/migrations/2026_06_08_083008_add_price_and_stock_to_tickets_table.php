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
    Schema::table('tickets', function (Blueprint $table) {
        // Menambahkan kolom price dan stock
        // Kita letakkan setelah kolom 'status' agar rapi
        $table->decimal('price', 10, 2)->default(0)->after('status');
        $table->integer('stock')->default(0)->after('price');
    });
}

public function down(): void
{
    Schema::table('tickets', function (Blueprint $table) {
        // Menghapus kolom jika ingin rollback
        $table->dropColumn(['price', 'stock']);
    });
}
};

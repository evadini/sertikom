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
        $table->dropColumn([
            'social_link',
            'ticket_type',
            'ticket_types',
            'price',
            'stock',
            'maps_link'
        ]);
    });
}

public function down(): void
{
    Schema::table('events', function (Blueprint $table) {
        // Jika ingin membatalkan (rollback), definisikan ulang tipe kolomnya di sini
        $table->string('social_link')->nullable();
        $table->string('ticket_type')->nullable();
        $table->string('ticket_types')->nullable();
        $table->decimal('price', 10, 2)->default(0);
        $table->integer('stock')->default(100);
        $table->string('maps_link')->nullable();
    });
}
};

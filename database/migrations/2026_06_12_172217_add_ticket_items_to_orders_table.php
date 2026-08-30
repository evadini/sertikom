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
        Schema::table('orders', function (Blueprint $table) {
            $table->json('ticket_items')->nullable()->after('total_amount');
            $table->string('ticket_type')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->decimal('price_per_ticket', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ticket_items');
            $table->string('ticket_type')->nullable(false)->change();
            $table->integer('quantity')->nullable(false)->change();
            $table->decimal('price_per_ticket', 10, 2)->nullable(false)->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('order_code')->unique(); // Kode unik order, contoh: ORD-20260604-XXXX
            $table->string('snap_token')->nullable(); // Token dari Midtrans
            $table->string('payment_url')->nullable(); // URL redirect Midtrans (fallback)
            $table->string('ticket_type'); // Jenis tiket yang dibeli
            $table->integer('quantity')->default(1); // Jumlah tiket
            $table->decimal('price_per_ticket', 10, 2); // Harga satuan tiket saat order dibuat
            $table->decimal('total_amount', 10, 2); // Total yang harus dibayar
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'cancelled'])->default('pending');
            $table->string('payment_type')->nullable(); // gopay, bank_transfer, dll
            $table->string('transaction_id')->nullable(); // ID transaksi dari Midtrans
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

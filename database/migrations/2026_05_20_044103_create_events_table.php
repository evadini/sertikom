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
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID Panitia pembuat event

        // STEP 1: TICKET
        $table->string('banner')->nullable(); // Poster event
        $table->string('name'); // Nama event
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        $table->string('location'); // Lokasi event
        $table->string('social_link')->nullable(); // Link instagram/sosmed
        $table->date('date'); // Tanggal pelaksanaan
        $table->time('time_start'); // Waktu mulai
        $table->time('time_end'); // Waktu selesai
        $table->string('ticket_type'); // Contoh: Reguler / VIP
         $table->string('ticket_types')->nullable(); // JSON untuk menyimpan berbagai jenis tiket (nama, harga, stok)
        $table->decimal('price', 10, 2)->default(0); // Harga tiket
        $table->integer('stock')->default(100); // Stok tiket

        // STEP 2: EVENT DETAIL
        $table->text('description'); // Deskripsi event
        $table->string('maps_link')->nullable(); // Link Google Maps
        $table->text('terms'); // Syarat & Ketentuan

        // STEP 3: ORGANISER
        $table->text('organiser_description')->nullable(); // Profil singkat penyelenggara
        $table->string('organiser_photo')->nullable(); // Foto organisasi / tim

        // SYSTEM FILTER
        $table->string('status')->default('pending'); // 'pending' (perlu persetujuan admin) atau 'published'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

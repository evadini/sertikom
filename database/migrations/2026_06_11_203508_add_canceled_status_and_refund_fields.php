<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('Active', 'Used', 'Expired', 'Canceled') DEFAULT 'Active'");

        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('refund_date')->nullable()->after('unpublished_at');
            $table->string('refund_location')->nullable()->after('refund_date');
            $table->text('refund_info')->nullable()->after('refund_location');
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('Active', 'Used', 'Expired') DEFAULT 'Active'");

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['refund_date', 'refund_location', 'refund_info']);
        });
    }
};

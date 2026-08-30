// database/migrations/xxxx_add_unpublish_reason_to_events_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->text('unpublish_reason')->nullable()->after('status');
            $table->timestamp('unpublished_at')->nullable()->after('unpublish_reason');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['unpublish_reason', 'unpublished_at']);
        });
    }
};

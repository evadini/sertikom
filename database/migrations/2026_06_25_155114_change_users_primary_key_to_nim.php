<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop foreign keys
        DB::statement('ALTER TABLE events DROP FOREIGN KEY events_user_id_foreign');
        DB::statement('ALTER TABLE tickets DROP FOREIGN KEY tickets_user_id_foreign');
        DB::statement('ALTER TABLE orders DROP FOREIGN KEY orders_user_id_foreign');
        DB::statement('ALTER TABLE role_applications DROP FOREIGN KEY role_applications_user_id_foreign');
        DB::statement('ALTER TABLE panitia_profiles DROP FOREIGN KEY panitia_profiles_user_id_foreign');

        // 2. Convert FK columns: BIGINT -> VARCHAR, populate with NIM
        $map = [
            'events'             => ['fk' => true,  'nullable' => false],
            'tickets'            => ['fk' => true,  'nullable' => false],
            'orders'             => ['fk' => true,  'nullable' => false],
            'role_applications'  => ['fk' => true,  'nullable' => false],
            'panitia_profiles'   => ['fk' => true,  'nullable' => false],
            'sessions'           => ['fk' => false, 'nullable' => true],
        ];

        foreach ($map as $table => $cfg) {
            $nullable = $cfg['nullable'] ? '' : ' NOT NULL';
            $after = $table === 'sessions' ? '' : ' AFTER user_id';
            DB::statement("ALTER TABLE {$table} ADD COLUMN user_nim VARCHAR(50){$nullable} {$after}");

            if (!$cfg['nullable']) {
                DB::statement("UPDATE {$table} t INNER JOIN users u ON t.user_id = u.id SET t.user_nim = u.nim");
            } else {
                DB::statement("UPDATE {$table} t INNER JOIN users u ON t.user_id = u.id SET t.user_nim = u.nim WHERE t.user_id IS NOT NULL");
            }

            DB::statement("ALTER TABLE {$table} DROP COLUMN user_id");
            DB::statement("ALTER TABLE {$table} RENAME COLUMN user_nim TO user_id");

            if (!$cfg['fk']) {
                DB::statement("ALTER TABLE {$table} ADD INDEX sessions_user_id_index (user_id)");
            }
        }

        // 3. Drop id column, make nim the primary key
        DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL');  // remove AUTO_INCREMENT
        DB::statement('ALTER TABLE users DROP PRIMARY KEY');
        DB::statement('ALTER TABLE users DROP COLUMN id');
        DB::statement('ALTER TABLE users MODIFY nim VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE users ADD PRIMARY KEY (nim)');

        // 4. Re-add foreign keys referencing users(nim)
        DB::statement('ALTER TABLE events ADD CONSTRAINT events_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(nim) ON DELETE CASCADE');
        DB::statement('ALTER TABLE tickets ADD CONSTRAINT tickets_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(nim) ON DELETE CASCADE');
        DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(nim) ON DELETE CASCADE');
        DB::statement('ALTER TABLE role_applications ADD CONSTRAINT role_applications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(nim) ON DELETE CASCADE');
        DB::statement('ALTER TABLE panitia_profiles ADD CONSTRAINT panitia_profiles_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(nim) ON DELETE CASCADE');
    }

    public function down(): void
    {
        throw new RuntimeException('This migration cannot be reversed.');
    }
};

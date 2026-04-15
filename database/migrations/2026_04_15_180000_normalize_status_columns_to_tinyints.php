<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasColumn('users', 'status')) {
            DB::statement("
                UPDATE users
                SET status = CASE LOWER(CAST(status AS CHAR))
                    WHEN 'pending' THEN 1
                    WHEN 'active' THEN 2
                    WHEN 'inactive' THEN 3
                    WHEN 'blocked' THEN 4
                    WHEN '1' THEN 1
                    WHEN '2' THEN 2
                    WHEN '3' THEN 3
                    WHEN '4' THEN 4
                    ELSE 2
                END
            ");

            DB::statement('ALTER TABLE users MODIFY status TINYINT NOT NULL DEFAULT 2');
        }

        if (Schema::hasColumn('bookings', 'status')) {
            DB::statement('ALTER TABLE bookings MODIFY status TINYINT NOT NULL DEFAULT 1');
            DB::statement("
                UPDATE bookings
                SET status = CASE status
                    WHEN 1 THEN 1
                    WHEN 2 THEN 2
                    WHEN 3 THEN 3
                    WHEN 4 THEN 4
                    ELSE 1
                END
            ");
        }

        if (Schema::hasColumn('properties', 'status')) {
            DB::statement('ALTER TABLE properties MODIFY status TINYINT NOT NULL DEFAULT 1');
            DB::statement("
                UPDATE properties
                SET status = CASE status
                    WHEN 1 THEN 1
                    WHEN 2 THEN 2
                    WHEN 3 THEN 3
                    ELSE 1
                END
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasColumn('users', 'status')) {
            DB::statement('ALTER TABLE users MODIFY status VARCHAR(255) NOT NULL DEFAULT "active"');
            DB::statement("
                UPDATE users
                SET status = CASE status
                    WHEN '1' THEN 'pending'
                    WHEN '2' THEN 'active'
                    WHEN '3' THEN 'inactive'
                    WHEN '4' THEN 'blocked'
                    ELSE 'active'
                END
            ");
        }

        if (Schema::hasColumn('bookings', 'status')) {
            DB::statement("ALTER TABLE bookings MODIFY status VARCHAR(20) NOT NULL DEFAULT 'pending'");
            DB::statement("
                UPDATE bookings
                SET status = CASE status
                    WHEN '2' THEN 'confirmed'
                    WHEN '3' THEN 'cancelled'
                    ELSE 'pending'
                END
            ");
            DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending'");
        }
    }
};

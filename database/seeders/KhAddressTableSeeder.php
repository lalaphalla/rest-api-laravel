<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Seed Khmer address reference tables from pre-generated SQL dumps.
 *
 * Chooses SQL files based on the default database connection (mysql or pgsql)
 * and executes them using DB::unprepared. The SQL assets must exist on the
 * 'generator' storage disk.
 *
 * This seeder is idempotent only if the SQL files themselves are; it does not
 * attempt to check for existing data.
 */
class KhAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * php artisan db:seed --class=KhAddressTableSeeder
     */
    public function run(): void
    {
        $dbName = config('database.default');

        if ($dbName === 'mysql') {
            DB::unprepared(Storage::disk('generator')->get('address.sql'));
            DB::unprepared(Storage::disk('generator')->get('address_2.sql'));
            DB::unprepared(Storage::disk('generator')->get('address_3.sql'));
        }

        if ($dbName === 'pgsql') {
            DB::unprepared(Storage::disk('generator')->get('kh_address.sql'));
            DB::unprepared(Storage::disk('generator')->get('kh_address_2.sql'));
            DB::unprepared(Storage::disk('generator')->get('kh_address_3.sql'));
        }
    }
}

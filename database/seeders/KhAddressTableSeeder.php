<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
<?php

namespace Database\Seeders;


use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Customer::factory()->count(25)->create();


        Customer::factory()
            ->count(10)
            ->hasInvoices(50)
            ->create();

        // Customer::factory()
        //     ->count(100)
        //     ->hasInvoices(3)
        //     ->create();

        // Customer::factory()
        //     ->count(25)
        //     ->create();
    }
}

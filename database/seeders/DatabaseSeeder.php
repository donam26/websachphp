<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            BookSeeder::class,
            SupplierSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}

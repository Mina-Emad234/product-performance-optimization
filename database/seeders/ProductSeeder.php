<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->truncate();

        $totalRecords = 300000;

        // Insert records in chunks to avoid memory overload
        $chunkSize = 5000; // Adjust the chunk size as per your system capacity

        // Insert the total records in chunks
        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            // Generate an array of data using the factory
            $products = \App\Models\Product::factory()->count($chunkSize)->make()->toArray();

            // Insert the data into the database using query builder
            DB::table('products')->insert($products);

            // Output progress to the console (optional)
            $this->command->info("Inserted " . ($i + $chunkSize) . " / " . $totalRecords . " records");
        }

        $this->command->info('Seeding completed!');
    }
}

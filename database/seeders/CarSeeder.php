<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = Car::factory(50)->make();
        $agency_ids = Agency::all()->pluck('id');
        $category_ids = Category::all()->pluck('id');

        foreach ($cars as $car) {
            $car->agency_id = $agency_ids->random();
            $car->category_id = $category_ids->random();
            $car->save();
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Warranty;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rentals = Rental::factory(50)->make();
        $car_plates = Car::all()->pluck('plate');
        $customer_ids = Customer::all()->pluck('id');
        $warranty_ids = Warranty::all()->pluck('id');

        foreach ($rentals as $rental) {
            $rental->car_plate = $car_plates->random();
            $rental->customer_id = $customer_ids->random();
            $rental->warranty_id = $warranty_ids->random();
            $rental->save();
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Amendment;
use App\Models\Rental;
use Illuminate\Database\Seeder;

class AmendmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amendments = Amendment::Factory(10)->make();
        $rental_ids = Rental::all()->pluck('id');

        foreach ($amendments as $amendment) {
            $amendment->rental_id = $rental_ids->random();
            $amendment->save();
        }
    }
}

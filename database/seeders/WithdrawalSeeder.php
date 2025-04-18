<?php

namespace Database\Seeders;

use App\Models\Rental;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;

class WithdrawalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $withdrawals = Withdrawal::factory(50)->make();
        $user_ids = User::all()->pluck('id');
        $rentals = Rental::all()->pluck('id');

        foreach ($withdrawals as $withdrawal) {
            $withdrawal->user_id = $user_ids->random();
            $withdrawal->rental_id = $rentals->random();
            $withdrawal->save();
        }
    }
}

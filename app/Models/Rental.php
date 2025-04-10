<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Une location de véhicule.
 *
 * @property int $rental_id
 * @property Carbon $rental_start
 * @property Carbon $rental_end
 * @property int $rental_nb_days
 * @property int $rental_total_price
 * @property int $cust_id
 * @property int $car_id
 * @property int $waranty_id
 */
class Rental extends Model
{
    //
}

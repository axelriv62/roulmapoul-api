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
 * @property float $rental_total_price
 * @property int $cust_id
 * @property int $car_id
 * @property int $waranty_id
 */
class Rental extends Model
{
    /**
     * @var string
     */
    protected $table = 'rental';

    /**
     * @var string
     */
    protected $primaryKey = 'rental_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'rental_start',
        'rental_end',
        'rental_nb_days',
        'rental_total_price',
        'cust_id',
        'car_id',
        'waranty_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'rental_start' => 'datetime',
        'rental_end' => 'datetime',
        'rental_nb_days' => 'integer',
        'rental_total_price' => 'float'
    ];
}

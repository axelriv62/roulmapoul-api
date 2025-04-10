<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Une location de véhicule.
 *
 * @property int $id
 * @property Carbon $start
 * @property Carbon $end
 * @property int $nb_days
 * @property float $total_price
 * @property int $customer_id
 * @property int $car_id
 * @property int $warranty_id
 */
class Rental extends Model
{
    /**
     * @var string
     */
    protected $table = 'rentals';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'start',
        'end',
        'nb_days',
        'total_price',
        'cust_id',
        'car_id',
        'warranty_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'nb_days' => 'integer',
        'total_price' => 'float'
    ];
}

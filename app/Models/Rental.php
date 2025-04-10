<?php

namespace App\Models;

use App\Enums\RentalState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Une location de véhicule.
 *
 * @property int $id
 * @property Carbon $start
 * @property Carbon $end
 * @property RentalState $state
 * @property int $nb_days
 * @property float $total_price
 * @property int $customer_id
 * @property string $car_plate
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
        'state',
        'nb_days',
        'total_price',
        'customer_id',
        'car_plate',
        'warranty_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'nb_days' => 'integer',
        'total_price' => 'float',
        'car_plate' => 'string',
    ];
}

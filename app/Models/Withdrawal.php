<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Un retrait d'une voiture de location.
 *
 * @property int $id
 * @property float $fuel_level
 * @property string $interior_condition
 * @property string $exterior_condition
 * @property float $mileage
 * @property Carbon datetime
 * @property string $comment
 * @property int $user_id
 * @property int $rental_id
 */
class Withdrawal extends Model
{
    /**
     * @var string
     */
    protected $table = 'withdrawals';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'fuel_level',
        'interior_condition',
        'exterior_condition',
        'mileage',
        'datetime',
        'comment',
        'user_id',
        'rental_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'fuel_level' => 'float',
        'mileage' => 'float',
        'datetime' => 'datetime'
    ];
}

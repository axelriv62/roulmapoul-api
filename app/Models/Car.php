<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une voiture.
 *
 * @property string $car_plate
 * TODO ajouter la property pour le type de la voiture après avoir créé l'enum (car_type)
 * @property string $car_condition
 * @property float $car_remaining_gas
 * @property float $car_price_day
 * @property int $category_id
 * @property int $agency_id
 */
class Car extends Model
{
    /**
     * @var string
     */
    public $keyType = 'string';
    
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'car';

    /**
     * @var string
     */
    protected $primaryKey = 'car_plate';

    /**
     * @var string[]
     */
    protected $fillable = [
        'car_plate',
        'car_type',
        'car_condition',
        'car_remaining_gas',
        'car_price_day',
        'category_id',
        'agency_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'car_remaining_gas' => 'float',
        'car_price_day' => 'float'
    ];
}

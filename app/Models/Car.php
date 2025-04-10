<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une voiture.
 *
 * @property string $plate
 * TODO ajouter la property pour le type de la voiture après avoir créé l'enum (type)
 * @property string $condition
 * @property float $remaining_gas
 * @property float $price_day
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
    protected $table = 'cars';

    /**
     * @var string
     */
    protected $primaryKey = 'plate';

    /**
     * @var string[]
     */
    protected $fillable = [
        'plate',
        'type',
        'condition',
        'remaining_gas',
        'price_day',
        'category_id',
        'agency_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'remaining_gas' => 'float',
        'price_day' => 'float'
    ];
}

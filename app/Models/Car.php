<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une voiture.
 *
 * @property string $car_plate
 * TODO ajouter la property pour le type de la voiture après avoir créé l'enum
 * @property string $car_condition
 * @property float $car_remaining_gas
 * @property float $car_price_day
 * @property int $category_id
 * @property int $agency_id
 */
class Car extends Model
{
    //
}

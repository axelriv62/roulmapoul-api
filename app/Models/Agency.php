<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une agence de location de voitures.
 *
 * @property int $id
 * @property string $num
 * @property string $street
 * @property string $zip
 * @property string $city
 * @property string $country
 */
class Agency extends Model
{
    /**
     * @var string
     */
    protected $table = 'agencies';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'num',
        'street',
        'zip',
        'city',
        'country'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'num' => 'string',
        'zip' => 'string'
    ];
}

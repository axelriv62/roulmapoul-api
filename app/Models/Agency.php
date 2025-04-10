<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une agence de location de voitures.
 *
 * @property int $agency_id
 * @property string $agency_num
 * @property string $agency_street
 * @property string $agency_zip
 * @property string $agency_city
 * @property string $agency_country
 */
class Agency extends Model
{
    /**
     * @var string
     */
    protected $table = 'agency';

    /**
     * @var string
     */
    protected $primaryKey = 'agency_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'agency_num',
        'agency_street',
        'agency_zip',
        'agency_city',
        'agency_country'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'agency_num' => 'string',
        'agency_zip' => 'string'
    ];
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Un permis de conduire possédé par un client.
 *
 * @property string $license_num
 * @property Carbon $license_acquirement_date
 * @property Carbon $license_distribution_date
 * @property string $license_country
 * @property int $cust_id
 */
class License extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';
    
    /**
     * @var string
     */
    protected $table = 'license';

    /**
     * @var string
     */
    protected $primaryKey = 'license_num';

    /**
     * @var string[]
     */
    protected $fillable = [
        'license_num',
        'license_acquirement_date',
        'license_distribution_date',
        'license_country',
        'cust_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'license_acquirement_date' => 'datetime',
        'license_distribution_date' => 'datetime'
    ];
}

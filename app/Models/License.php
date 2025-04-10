<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Un permis de conduire possédé par un client.
 *
 * @property string $num
 * @property Carbon $acquirement_date
 * @property Carbon $distribution_date
 * @property string $country
 * @property int $customer_id
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
    protected $table = 'licenses';

    /**
     * @var string
     */
    protected $primaryKey = 'num';

    /**
     * @var string[]
     */
    protected $fillable = [
        'num',
        'acquirement_date',
        'distribution_date',
        'country',
        'customer_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'acquirement_date' => 'date',
        'distribution_date' => 'date'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

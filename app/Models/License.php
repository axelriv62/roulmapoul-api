<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\LicenseFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Un permis de conduire possédé par un client.
 *
 * @property string $num
 * @property Carbon $birthday
 * @property Carbon $acquirement_date
 * @property Carbon $distribution_date
 * @property string $country
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Customer $customer
 * @method static Builder<static>|License newModelQuery()
 * @method static Builder<static>|License newQuery()
 * @method static Builder<static>|License query()
 * @method static Builder<static>|License whereAcquirementDate($value)
 * @method static Builder<static>|License whereCountry($value)
 * @method static Builder<static>|License whereCreatedAt($value)
 * @method static Builder<static>|License whereCustomerId($value)
 * @method static Builder<static>|License whereDistributionDate($value)
 * @method static Builder<static>|License whereNum($value)
 * @method static Builder<static>|License whereUpdatedAt($value)
 * @mixin Eloquent
 */
class License extends Model
{
    /** @use HasFactory<LicenseFactory> */
    use HasFactory;

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
     * @var string
     */
    protected $dateFormat = 'Y-m-d';

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

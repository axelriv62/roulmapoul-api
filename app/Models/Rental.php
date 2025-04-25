<?php

namespace App\Models;

use App\Enums\RentalState;
use Carbon\Carbon;
use Database\Factories\RentalFactory;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, Amendment> $amendments
 * @property-read int|null $amendments_count
 * @property-read Car $car
 * @property-read Customer $customer
 * @property-read Collection<int, Document> $documents
 * @property-read int|null $documents_count
 * @property-read Handover|null $handover
 * @property-read Collection<int, Option> $options
 * @property-read int|null $options_count
 * @property-read Warranty $warranty
 * @property-read Withdrawal|null $withdrawal
 *
 * @method static Builder<static>|Rental newModelQuery()
 * @method static Builder<static>|Rental newQuery()
 * @method static Builder<static>|Rental query()
 * @method static Builder<static>|Rental whereCarPlate($value)
 * @method static Builder<static>|Rental whereCreatedAt($value)
 * @method static Builder<static>|Rental whereCustomerId($value)
 * @method static Builder<static>|Rental whereEnd($value)
 * @method static Builder<static>|Rental whereId($value)
 * @method static Builder<static>|Rental whereNbDays($value)
 * @method static Builder<static>|Rental whereStart($value)
 * @method static Builder<static>|Rental whereState($value)
 * @method static Builder<static>|Rental whereTotalPrice($value)
 * @method static Builder<static>|Rental whereUpdatedAt($value)
 * @method static Builder<static>|Rental whereWarrantyId($value)
 * @method static RentalFactory factory($count = null, $state = [])
 *
 * @mixin Eloquent
 */
class Rental extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

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
        'warranty_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'start' => 'date',
        'end' => 'date',
        'nb_days' => 'integer',
        'total_price' => 'float',
        'car_plate' => 'string',
    ];

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d';

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'rental_option', 'rental_id', 'option_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_plate', 'plate');
    }

    public function warranty(): BelongsTo
    {
        return $this->belongsTo(Warranty::class);
    }

    public function handover(): HasOne
    {
        return $this->hasOne(Handover::class);
    }

    public function withdrawal(): HasOne
    {
        return $this->hasOne(Withdrawal::class);
    }

    public function amendments(): HasMany
    {
        return $this->hasMany(Amendment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}

<?php

namespace App\Models;

use App\Enums\RentalState;
use Carbon\Carbon;
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
 */
class Rental extends Model
{
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
        'warranty_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'nb_days' => 'integer',
        'total_price' => 'float',
        'car_plate' => 'string',
    ];

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

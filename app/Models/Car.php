<?php

namespace App\Models;

use App\Enums\CarAvailability;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Une voiture.
 *
 * @property string $plate
 * @property CarAvailability $availability
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
        'availability',
        'price_day',
        'category_id',
        'agency_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'price_day' => 'float'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'car_plate', 'plate');
    }
}

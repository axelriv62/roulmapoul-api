<?php

namespace App\Models;

use App\Enums\CarAvailability;
use Database\Factories\CarFactory;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Une voiture.
 *
 * @property string $plate
 * @property CarAvailability $availability
 * @property float $price_day
 * @property int $category_id
 * @property int $agency_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Agency $agency
 * @property-read Category $category
 * @property-read Collection<int, Rental> $rentals
 * @property-read int|null $rentals_count
 * @method static Builder<static>|Car newModelQuery()
 * @method static Builder<static>|Car newQuery()
 * @method static Builder<static>|Car query()
 * @method static Builder<static>|Car whereAgencyId($value)
 * @method static Builder<static>|Car whereAvailability($value)
 * @method static Builder<static>|Car whereCategoryId($value)
 * @method static Builder<static>|Car whereCreatedAt($value)
 * @method static Builder<static>|Car wherePlate($value)
 * @method static Builder<static>|Car wherePriceDay($value)
 * @method static Builder<static>|Car whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Car extends Model
{
    /** @use HasFactory<CarFactory> */
    use HasFactory;

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

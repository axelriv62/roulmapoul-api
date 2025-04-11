<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Une garantie.
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Rental> $rentals
 * @property-read int|null $rentals_count
 * @method static Builder<static>|Warranty newModelQuery()
 * @method static Builder<static>|Warranty newQuery()
 * @method static Builder<static>|Warranty query()
 * @method static Builder<static>|Warranty whereCreatedAt($value)
 * @method static Builder<static>|Warranty whereId($value)
 * @method static Builder<static>|Warranty whereName($value)
 * @method static Builder<static>|Warranty wherePrice($value)
 * @method static Builder<static>|Warranty whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Warranty extends Model
{
    /**
     * @var string
     */
    protected $table = 'warranties';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'price'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'price' => 'float',
    ];

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }
}

<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * Une option sélectionnée pour une location.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Rental> $rentals
 * @property-read int|null $rentals_count
 * @method static Builder<static>|Option newModelQuery()
 * @method static Builder<static>|Option newQuery()
 * @method static Builder<static>|Option query()
 * @method static Builder<static>|Option whereCreatedAt($value)
 * @method static Builder<static>|Option whereDescription($value)
 * @method static Builder<static>|Option whereId($value)
 * @method static Builder<static>|Option whereName($value)
 * @method static Builder<static>|Option wherePrice($value)
 * @method static Builder<static>|Option whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Option extends Model
{
    /**
     * @var string
     */
    protected $table = 'options';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'price'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'price' => 'float',
    ];

    public function rentals(): BelongsToMany
    {
        return $this->belongsToMany(Rental::class);
    }
}

<?php

namespace App\Models;

use Database\Factories\AmendmentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Un avenant.
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string $content
 * @property int $rental_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Rental $rental
 *
 * @method static Builder<static>|Amendment newModelQuery()
 * @method static Builder<static>|Amendment newQuery()
 * @method static Builder<static>|Amendment query()
 * @method static Builder<static>|Amendment whereContent($value)
 * @method static Builder<static>|Amendment whereCreatedAt($value)
 * @method static Builder<static>|Amendment whereId($value)
 * @method static Builder<static>|Amendment whereName($value)
 * @method static Builder<static>|Amendment wherePrice($value)
 * @method static Builder<static>|Amendment whereRentalId($value)
 * @method static Builder<static>|Amendment whereUpdatedAt($value)
 * @method static AmendmentFactory factory($count = null, $state = [])
 *
 * @mixin Eloquent
 */
class Amendment extends Model
{
    /** @use HasFactory<AmendmentFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'amendments';

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
        'price',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'price' => 'float',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }
}

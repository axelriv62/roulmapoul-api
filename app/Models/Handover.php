<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\HandoverFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Un retour d'une voiture de location.
 *
 * @property int $id
 * @property float $fuel_level
 * @property string $interior_condition
 * @property string $exterior_condition
 * @property float $mileage
 * @property Carbon $datetime
 * @property string $comment
 * @property int $rental_id
 * @property-read Rental|null $rental
 *
 * @method static Builder<static>|Handover newModelQuery()
 * @method static Builder<static>|Handover newQuery()
 * @method static Builder<static>|Handover query()
 *
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Customer $customer
 *
 * @method static HandoverFactory factory($count = null, $state = [])
 * @method static Builder<static>|Handover whereComment($value)
 * @method static Builder<static>|Handover whereCreatedAt($value)
 * @method static Builder<static>|Handover whereCustomerId($value)
 * @method static Builder<static>|Handover whereDatetime($value)
 * @method static Builder<static>|Handover whereExteriorCondition($value)
 * @method static Builder<static>|Handover whereFuelLevel($value)
 * @method static Builder<static>|Handover whereId($value)
 * @method static Builder<static>|Handover whereInteriorCondition($value)
 * @method static Builder<static>|Handover whereMileage($value)
 * @method static Builder<static>|Handover whereRentalId($value)
 * @method static Builder<static>|Handover whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Handover extends Model
{
    /** @use HasFactory<HandoverFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'handovers';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var string[]
     */
    protected $fillable = [
        'fuel_level',
        'interior_condition',
        'exterior_condition',
        'mileage',
        'datetime',
        'comment',
        'customer_id',
        'rental_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'fuel_level' => 'float',
        'mileage' => 'float',
        'datetime' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }
}

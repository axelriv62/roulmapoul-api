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
 * @property Carbon datetime
 * @property string $comment
 * @property int $user_id
 * @property int $rental_id
 * @property-read Rental|null $rental
 * @method static Builder<static>|Handover newModelQuery()
 * @method static Builder<static>|Handover newQuery()
 * @method static Builder<static>|Handover query()
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
    protected $dateFormat = "Y-m-d H:i:s";

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
        'user_id',
        'rental_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'fuel_level' => 'float',
        'mileage' => 'float',
        'datetime' => 'datetime'
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

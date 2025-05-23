<?php

namespace App\Models;

use Database\Factories\WithdrawalFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Un retrait d'une voiture de location.
 *
 * @property int $id
 * @property float $fuel_level
 * @property string $interior_condition
 * @property string $exterior_condition
 * @property float $mileage
 * @property Carbon $datetime
 * @property string $comment
 * @property int $rental_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Rental $rental
 *
 * @method static Builder<static>|Withdrawal newModelQuery()
 * @method static Builder<static>|Withdrawal newQuery()
 * @method static Builder<static>|Withdrawal query()
 * @method static Builder<static>|Withdrawal whereComment($value)
 * @method static Builder<static>|Withdrawal whereCreatedAt($value)
 * @method static Builder<static>|Withdrawal whereDatetime($value)
 * @method static Builder<static>|Withdrawal whereExteriorCondition($value)
 * @method static Builder<static>|Withdrawal whereFuelLevel($value)
 * @method static Builder<static>|Withdrawal whereId($value)
 * @method static Builder<static>|Withdrawal whereInteriorCondition($value)
 * @method static Builder<static>|Withdrawal whereMileage($value)
 * @method static Builder<static>|Withdrawal whereRentalId($value)
 * @method static Builder<static>|Withdrawal whereUpdatedAt($value)
 * @method static Builder<static>|Withdrawal whereUserId($value)
 *
 * @property int $customer_id
 * @property-read Customer $customer
 *
 * @method static WithdrawalFactory factory($count = null, $state = [])
 * @method static Builder<static>|Withdrawal whereCustomerId($value)
 *
 * @mixin Eloquent
 */
class Withdrawal extends Model
{
    /** @use HasFactory<WithdrawalFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'withdrawals';

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

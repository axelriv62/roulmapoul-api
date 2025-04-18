<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\CustomerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Un client.
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $num
 * @property string $street
 * @property string $zip
 * @property string $city
 * @property string $country
 * @property string $num_bill
 * @property string $street_bill
 * @property string $zip_bill
 * @property string $city_bill
 * @property string $country_bill
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read License|null $license
 * @property-read Collection<int, Rental> $rentals
 * @property-read int|null $rentals_count
 * @property-read User $user
 * @method static Builder<static>|Customer newModelQuery()
 * @method static Builder<static>|Customer newQuery()
 * @method static Builder<static>|Customer query()
 * @method static Builder<static>|Customer whereBirthday($value)
 * @method static Builder<static>|Customer whereCity($value)
 * @method static Builder<static>|Customer whereCityBill($value)
 * @method static Builder<static>|Customer whereCountry($value)
 * @method static Builder<static>|Customer whereCountryBill($value)
 * @method static Builder<static>|Customer whereCreatedAt($value)
 * @method static Builder<static>|Customer whereEmail($value)
 * @method static Builder<static>|Customer whereFirstName($value)
 * @method static Builder<static>|Customer whereId($value)
 * @method static Builder<static>|Customer whereLastName($value)
 * @method static Builder<static>|Customer whereNum($value)
 * @method static Builder<static>|Customer whereNumBill($value)
 * @method static Builder<static>|Customer wherePhone($value)
 * @method static Builder<static>|Customer whereStreet($value)
 * @method static Builder<static>|Customer whereStreetBill($value)
 * @method static Builder<static>|Customer whereUpdatedAt($value)
 * @method static Builder<static>|Customer whereUserId($value)
 * @method static Builder<static>|Customer whereZip($value)
 * @method static Builder<static>|Customer whereZipBill($value)
 * @property-read Collection<int, Handover> $handovers
 * @property-read int|null $handovers_count
 * @property-read Collection<int, Withdrawal> $withdrawals
 * @property-read int|null $withdrawals_count
 * @method static CustomerFactory factory($count = null, $state = [])
 * @mixin Eloquent
 */
class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'customers';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birthday',
        'email',
        'phone',
        'num',
        'street',
        'zip',
        'city',
        'country',
        'num_bill',
        'street_bill',
        'zip_bill',
        'city_bill',
        'country_bill',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'birthday' => 'date',
        'num' => 'string',
        'zip' => 'string',
        'num_bill' => 'string',
        'zip_bill' => 'string'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function license(): HasOne
    {
        return $this->hasOne(License::class);
    }

    public function handovers(): HasMany
    {
        return $this->hasMany(HandOver::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }
}

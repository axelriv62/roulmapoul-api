<?php

namespace App\Models;

use Database\Factories\AgencyFactory;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Une agence de location de voitures.
 *
 * @property int $id
 * @property string $num
 * @property string $street
 * @property string $zip
 * @property string $city
 * @property string $country
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Car> $cars
 * @property-read int|null $cars_count
 * @method static Builder<static>|Agency newModelQuery()
 * @method static Builder<static>|Agency newQuery()
 * @method static Builder<static>|Agency query()
 * @method static Builder<static>|Agency whereCity($value)
 * @method static Builder<static>|Agency whereCountry($value)
 * @method static Builder<static>|Agency whereCreatedAt($value)
 * @method static Builder<static>|Agency whereId($value)
 * @method static Builder<static>|Agency whereNum($value)
 * @method static Builder<static>|Agency whereStreet($value)
 * @method static Builder<static>|Agency whereUpdatedAt($value)
 * @method static Builder<static>|Agency whereZip($value)
 * @mixin Eloquent
 */
class Agency extends Model
{
    /** @use HasFactory<AgencyFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'agencies';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'num',
        'street',
        'zip',
        'city',
        'country'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'num' => 'string',
        'zip' => 'string'
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}

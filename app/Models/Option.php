<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Une option sélectionnée pour une location.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $price
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

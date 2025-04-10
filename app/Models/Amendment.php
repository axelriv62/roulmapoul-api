<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Un avenant.
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string $content
 * @property int $rental_id
 */
class Amendment extends Model
{
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
        'price' => 'float'
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }
}

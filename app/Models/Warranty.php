<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une garantie.
 *
 * @property int $warranty_id
 * @property string $warranty_name
 * @property float $warranty_price
 */
class Warranty extends Model
{
    /**
     * @var string
     */
    protected $table = 'warranty';

    /**
     * @var string
     */
    protected $primaryKey = 'warranty_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'warranty_name',
        'warranty_price'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'warranty_price' => 'float',
    ];
}

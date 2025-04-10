<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une garantie.
 *
 * @property int $id
 * @property string $name
 * @property float $price
 */
class Warranty extends Model
{
    /**
     * @var string
     */
    protected $table = 'warranties';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'price'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'price' => 'float',
    ];
}

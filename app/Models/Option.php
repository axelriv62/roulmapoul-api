<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une option sélectionnée pour une location.
 *
 * @property int $option_id
 * @property string $option_name
 * @property string $option_description
 * @property float $option_price
 */
class Option extends Model
{
    /**
     * @var string
     */
    protected $table = 'option';

    /**
     * @var string
     */
    protected $primaryKey = 'option_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'option_name',
        'option_description',
        'option_price'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'option_price' => 'float',
    ];
}

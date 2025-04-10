<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Un avenant.
 *
 * @property int $amendment_id
 * @property string $amendment_name
 * @property string $amendment_description
 */
class Amendment extends Model
{
    /**
     * @var string
     */
    protected $table = 'amendment';

    /**
     * @var string
     */
    protected $primaryKey = 'amendment_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'amendment_name',
        'amendment_description'
    ];
}

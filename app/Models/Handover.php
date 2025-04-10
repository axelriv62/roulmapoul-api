<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Un retour.
 *
 * @property int $id
 * @property Carbon datetime
 * @property int $user_id
 * @property int $rental_id
 */
class Handover extends Model
{
    /**
     * @var string
     */
    protected $table = 'handover';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'datetime',
        'user_id',
        'rental_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'datetime' => 'datetime',
    ];
}

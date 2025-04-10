<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Un retour.
 *
 * @property int $handover_id
 * @property Carbon handover_datetime
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
    protected $primaryKey = 'handover_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'handover_datetime',
        'user_id',
        'rental_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'handover_datetime' => 'datetime',
    ];
}

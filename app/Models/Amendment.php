<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Un avenant.
 *
 * @property int $id
 * @property string $name
 * @property string $description
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
        'description'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une catégorie de véhicule.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 */
class Category extends Model
{
    /**
     * @var string
     */
    protected $table = 'categories';

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

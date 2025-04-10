<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une catégorie de véhicule.
 *
 * @property int $category_id
 * @property string $category_name
 * @property string $category_description
 */
class Category extends Model
{
    /**
     * @var string
     */
    protected $table = 'category';

    /**
     * @var string
     */
    protected $primaryKey = 'category_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'category_name',
        'category_description'
    ];
}

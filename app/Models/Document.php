<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Model;

/**
 * Un document associé à une location, il peut s'agir d'une facture ou d'un document comportant les informations d'un retrait ou d'un retour.
 *
 * @property int $id
 * @property DocumentType $type
 * @property string $url
 * @property int $rental_id
 */
class Document extends Model
{
    /**
     * @var string
     */
    protected $table = 'documents';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'url',
        'rental_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'url' => 'string'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Un document associé à une location, il peut s'agir d'une facture ou d'un document comportant les informations d'un retrait ou d'un retour.
 *
 * @property int $doc_id
 * TODO ajouter la propriété doc_type quand l'enum sera créée
 * @property string $doc_url
 * @property int $rental_id
 */
class Document extends Model
{
    /**
     * @var string
     */
    protected $table = 'document';

    /**
     * @var string
     */
    protected $primaryKey = 'doc_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'doc_url',
        'rental_id'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'doc_url' => 'string'
    ];
}

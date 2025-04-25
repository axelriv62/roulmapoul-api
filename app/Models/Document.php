<?php

namespace App\Models;

use App\Enums\DocumentType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Un document associé à une location, il peut s'agir d'une facture ou d'un document comportant les informations d'un retrait ou d'un retour.
 *
 * @property int $id
 * @property DocumentType $type
 * @property string $url
 * @property int $rental_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Rental $rental
 *
 * @method static Builder<static>|Document newModelQuery()
 * @method static Builder<static>|Document newQuery()
 * @method static Builder<static>|Document query()
 * @method static Builder<static>|Document whereCreatedAt($value)
 * @method static Builder<static>|Document whereId($value)
 * @method static Builder<static>|Document whereRentalId($value)
 * @method static Builder<static>|Document whereType($value)
 * @method static Builder<static>|Document whereUpdatedAt($value)
 * @method static Builder<static>|Document whereUrl($value)
 *
 * @mixin Eloquent
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
        'rental_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'url' => 'string',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }
}

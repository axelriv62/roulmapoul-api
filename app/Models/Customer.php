<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Un client.
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property Carbon $birthday
 * @property string $email
 * @property string $phone
 * @property string $num
 * @property string $street
 * @property string $zip
 * @property string $city
 * @property string $country
 * @property string $num_bill
 * @property string $street_bill
 * @property string $zip_bill
 * @property string $city_bill
 * @property string $country_bill
 * @property int $user_id
 */
class Customer extends Model
{
    /**
     * @var string
     */
    protected $table = 'customers';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birthday',
        'email',
        'phone',
        'num',
        'street',
        'zip',
        'city',
        'country',
        'num_bill',
        'street_bill',
        'zip_bill',
        'city_bill',
        'country_bill',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'birthday' => 'date',
        'num' => 'string',
        'zip' => 'string',
        'num_bill' => 'string',
        'zip_bill' => 'string'
    ];
}

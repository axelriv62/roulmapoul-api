<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Un client.
 *
 * @property int $cust_id
 * @property string $cust_first_name
 * @property string $cust_last_name
 * @property Carbon $cust_birthday
 * @property string $cust_email
 * @property string $cust_phone
 * @property string $cust_num
 * @property string $cust_street
 * @property string $cust_zip
 * @property string $cust_city
 * @property string $cust_country
 * @property string $cust_num_bill
 * @property string $cust_street_bill
 * @property string $cust_zip_bill
 * @property string $cust_city_bill
 * @property string $cust_country_bill
 * @property string $user_id
 */
class Customer extends Model
{
    /**
     * @var string
     */
    protected $table = 'customer';

    /**
     * @var string
     */
    protected $primaryKey = 'customer_id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'cust_first_name',
        'cust_last_name',
        'cust_birthday',
        'cust_email',
        'cust_phone',
        'cust_num',
        'cust_street',
        'cust_zip',
        'cust_city',
        'cust_country',
        'cust_num_bill',
        'cust_street_bill',
        'cust_zip_bill',
        'cust_city_bill',
        'cust_country_bill',
    ];

    protected $casts = [
        'cust_birthday' => 'date',
        'cust_num' => 'string',
        'cust_zip' => 'string',
        'cust_num_bill' => 'string',
        'cust_zip_bill' => 'string'
    ];
}

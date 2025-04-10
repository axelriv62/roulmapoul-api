<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * @var string
     */
    protected $table = 'customer';

    /**
     * @var string[]
     */
    protected $fillable = [
        'cust_first_name',
        'cust_last_name',
        'cust_birthday',
        'cust_mail',
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
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Un permis de conduire possédé par un client.
 *
 * @property string $license_num
 * @property Carbon $license_acquirement_date
 * @property Carbon $license_distribution_date
 * @property string $license_country
 * @property int $cust_id
 */
class License extends Model
{
    //
}

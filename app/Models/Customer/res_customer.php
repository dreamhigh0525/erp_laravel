<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class res_customer extends Model
{
    use softDeletes;
    protected $table = 'res_customers';
    protected $guarded = [];
}

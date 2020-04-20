<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class account_account extends Model
{
    use softDeletes;
    protected $fillable = [
        'name',
        'currency_id',
        'code',
        'deprecated ',
        'type',
        'internal_type',
        'internal_group',
        'reconcile',
        'note',
        'company_id',
        'root_id'
    ];
    public function company()
    {
        return $this->hasOne('App\res_company','id','company_id');
    }
    public function account_type()
    {
        return $this->hasOne('App\Models\Accounting\account_account_type','id','type');
    }
}

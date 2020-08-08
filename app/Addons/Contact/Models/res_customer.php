<?php

namespace App\Addons\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class res_customer extends Model
{
    use softDeletes;
    protected $table = 'res_customers';
    protected $fillable = [
        'name','display_name','parent_id','ref','lag','tz','currency_id','bank_account',
        'website','credit','debit','active','address','street','street2','zip','city','state_id','country_id','email','phone','mobile','industry_id','sales','payment_terms','note','user_id','receivable_account','logo','journal',
    ];
    public function state()
    {
        return $this->hasOne('App\Models\World_database\res_country_state','id','state_id');
    }
    public function move_lines()
    {
        return $this->hasMany('App\Models\Accounting\account_move_line','partner_id','id')->where('account_internal_type','receivable');
    }
    public function currency()
    {
        return $this->hasOne('App\Models\Currency\res_currency','id','currency_id');
    }
    public function country()
    {
        return $this->hasOne('App\Models\World_database\res_country','id','country_id');
    }
}

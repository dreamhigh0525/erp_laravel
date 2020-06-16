<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class account_move_line extends Model
{
    protected $fillable = [
        'account_move_id','account_move_name','date','ref','parent_state',
        'journal_id','company_id','company_currency_id','account_id',
        'account_internal_type','product_id','name','quantity','price_unit',
        'price_total','debit','credit','balance','reconciled','currency_id',
        'partner_id','create_uid',
    ];
}

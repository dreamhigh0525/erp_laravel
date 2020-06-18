<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class customer_dept extends Model
{
    protected $table = 'customer_debt';
    protected $primaryKey = 'invoice_no';
    protected $fillable = [
        'invoice_no','customer_id',
        'invoice_date','due_date','payment','over', 'journal',   
        'status','total'
    ];
    public function journal()
    {
        return $this->hasOne('App\Models\Accounting\account_journal','id','journal');
    }
    public function customer()
    {
        return $this->hasOne('App\Models\Customer\res_customer','id','customer_id');
    }
}

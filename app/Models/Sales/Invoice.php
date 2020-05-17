<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no', 'invoice_date', 'due_date',
        'title', 'sub_total', 'discount',
        'grand_total', 'client','deliver',
        'client_address','approved','status','sales'
    ];

    public function products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
    public function customer()
    {
        return $this->hasOne('App\Models\Customer\res_customer','id','client');
    }
    public function payment()
    {
        return $this->hasOne('App\Models\Customer\customer_dept','invoice_no','invoice_no');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ardiscountshdr extends Model
{
    //
    protected $table='m_discounts_hdr_t';

    protected $primaryKey='ar_discount_hdr_id';
  protected $fillable = [
        'ar_discount_hdr_id','discount_name','discount_applylevel', 'discount_currency_id','default_discount_amount','start_date',
        'end_date','active','remarks','discount_at_partialpayment','save_status'
    ];
}

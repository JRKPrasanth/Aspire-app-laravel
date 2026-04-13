<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
   protected $table = 'p_payments_hdr_t';
   protected $primaryKey ='payment_hdr_id';
   protected $fillable =['payment_number','payment_batches_hdr_id','payment_date','batch_total_amount','supplier_id','supplier_site_id','organization_id','remarks','bank_id','account_no','payment_reference','payment_type_id','cheque_no'];
}

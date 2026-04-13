<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paymentdetails extends Model
{
     protected $table='p_payments_t';
      protected $primaryKey='payment_id';
      protected $fillable =['discount_amount','penality_amount','reference_id','payment_id','payment_number','po_invoice_id','supplier_id','supplier_site_id','payment_date','payment_amount','account_code_id','payment_type_id','cheque_no','account_no','bank_id','remarks','payment_reference'];

}

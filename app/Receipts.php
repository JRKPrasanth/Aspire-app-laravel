<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipts extends Model
{
   protected $table = 's_receipts_hdr_t';
   protected $primaryKey ='receipt_hdr_id';
   protected $fillable =['receipt_number','receipt_batch_hdr_id','receipt_date','receipt_status','batch_total_amount','customer_id','customer_site_id','organization_id','remarks','bank_id','account_no','receipt_reference','receipt_type_id','cheque_no'];

}

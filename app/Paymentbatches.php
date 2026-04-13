<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paymentbatches extends Model
{
   protected $table = 'p_payment_batches_hdr_t';
    protected $primaryKey ='payment_batches_hdr_id';
    protected $fillable =['payment_batches_hdr_id','payment_batch_name','supplier_id','suppliersite_id','payment_batch_status','organization_id','remarks'];
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class supplierupload extends Model
{
   protected $table='p_supplier_upload_t';
   protected $primaryKey='supplier_upload_id';
   protected $fillable =['supplier_upload_id','supplier_number','supplier_name','supplier_alternate_name','supplier_type','default_payment_method','default_payment_terms','default_pricelist','freight_carrier','account_structure','freight_term','delivery_term','insurance_term','tds_percentage','default_bank','billing_address','convertcustosup','customer_name','contact_person','gst_no','contact_number','overdue','customer','batch_status','batch_comments','batch_date','batch_name','tds_applicable','customer_id','tds_account','active'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

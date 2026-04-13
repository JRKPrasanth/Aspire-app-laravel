<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
      protected $table='m_supplier_t';
      protected $primaryKey='supplier_id';
      protected $fillable =['supplier_type_id','supplier_name','approver_id','savestatus','supplier_number','gst_no','supplier_alternate_name','default_payment_method_id','default_payment_terms_id','default_pricelist_id','billing_address','authentication','pan_number','customer_id','customer_name','account_structure_id','tds_account_id','tds_applicable','tds_percentage','tcs_account_id','tcs_applicable','tcs_percentage','supplier_status','delivery_terms_id','default_bank_id','overdue','insurance_term_id','calculation_id','overdue_date','pf_registration_no','active','frieghtterm_id','frieghtcarriers_id','last_updated_by','msme_status'];
}

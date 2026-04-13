<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcontractsupplier extends Model
{
      protected $table='m_subcontract_supplier_t';
      protected $primaryKey='subcontract_supplier_id';
      protected $fillable =['supplier_type_id','subcontract_name','subcontract_number','gst_no','subcontract_alternate_name','default_payment_method_id','default_payment_terms_id','default_pricelist_id','billing_address','authentication','pan_number','customer_id','customer_name','account_structure_id','tds_account_id','tds_applicable','tds_percentage','tcs_account_id','tcs_applicable','tcs_percentage','supplier_status','delivery_terms_id','default_bank_id','overdue','insurance_term_id','calculation_id','overdue_date','pf_registration_no','active'];

}

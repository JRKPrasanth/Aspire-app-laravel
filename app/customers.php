<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    protected $table='m_customers_t';
      protected $primaryKey='customer_id';
      protected $fillable = [
          'customer_name','customer_number', 'customer_type_id','approver_id','alternate_name','sales_person','gst_no','default_payment_terms_id',  'billing_address','contact_person','contact_number','pricelist_id','default_payment_method_id','overdue','customer_count','customer_category',
          'line_of_business','available_credit','tds_applicable','tds_percentage','tds_account_id','tcs_applicable','tcs_percentage','tcs_account_id','ar_frieghtcarriers_hdr_id','reward_opening_point','ar_discount_hdr_id','credit_limit',  'company_additional_info','reward_point','maximum_credit','credit_check','default_bank','company_id','location_id','organization_id','account_structure_id','savestatus','status','created_by','schemes','active'
        ];
     }

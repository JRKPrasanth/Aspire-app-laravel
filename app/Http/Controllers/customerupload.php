<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customerupload extends Model
{
	protected $table="s_customerupload_t";
	protected $primaryKey="customerupload_id";
	protected $fillable = ['customer_number','customer_name','customer_type','alternate_name','sales_person','gst_no','billing_address','contact_person','contact_number','overdue','pricelist','default_payment_terms','default_payment_method','customer_category','reward_opening_point','reward_point','ar_discount_hdr','maximum_credit','available_credit','credit_limit','credit_check','ar_frieghtcarriers_hdr','company_additional_info','batch_status','batch_name'
	];
}

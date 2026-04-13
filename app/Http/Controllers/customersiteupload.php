<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customersiteupload extends Model
{
    protected $table="s_customersites_upload_t";
	protected $primaryKey="customersite_upload_id";
	public $foreignKey='';	
	protected $fillable=['customer_name','customer_site_number','customer_site_name','address','city','state','country','pincode','organization_id','company_id','created_at','updated_at','site_type','contact_number','contact_person','batch_name','batch_date','batch_status','batch_comments','org_locationcode_id','tan_no','gst_no','active','contact_email'];	
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class suppliersiteupload extends Model
{
	protected $table='p_suppliersites_upload_t';
    protected $primaryKey='suppliersite_upload_id';
    protected $fillable = ['supplier_name','supplier_site_number','supplier_site_name','address', 'site_type','city','state','country','pincode','contact_number','contact_person','batch_name','batch_date','batch_status','batch_comments','org_locationcode_id'];
}

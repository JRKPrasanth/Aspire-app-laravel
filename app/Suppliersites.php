<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Suppliersites extends Model
{
    protected $table='m_supplier_sites_t';
    protected $primaryKey='supplier_site_id';
    protected $fillable = ['supplier_id','supplier_site_id','supplier_site_number', 'site_type','supplier_site_name','address','city','state',
						'country','pincode','contact_number','contact_person','contact_mail','gst_number','tan_no','primary_address','active','last_updated_by'];

     }

<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class customersites extends Model
{
    protected $table='m_customer_sites_t';
      protected $primaryKey='customer_site_id';
protected $foreignKey='customer_id';
			protected $fillable = [
						'customer_site_number', 'site_type','customer_site_name','address','city','state','copy_site_row','country','gst_no','pincode','contact_number','contact_person','contact_mail','location_id','company_id','organization_id','primary_address','active','tan_no'];

     }

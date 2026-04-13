<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcontractsites extends Model
{
    protected $table='m_subcontract_sites_t';
    protected $primaryKey='subcontract_site_id';
    protected $fillable = ['subcontract_supplier_id','subcontract_site_id','subcontract_site_number', 'site_type','subcontract_site_name','address','city','state',
						'country','pincode','contact_number','contact_person','contact_mail','gst_number','primary_address'];

     }

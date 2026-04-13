<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poenquiryheader extends Model
{
     protected $table='po_enquiry_header_t';
    protected $primaryKey='po_enquiry_header_id';
//	protected $fillable = [
//        'po_enquiry_header_id','enquiry_number','enquiry_date', 'supplier_id','supplier_site_id','project_id','remarks'
//    ];
}

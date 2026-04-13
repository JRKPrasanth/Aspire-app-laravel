<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseenquirylines extends Model
{
    public $table='p_enquiry_lines_t';
	public $primaryKey='enquiry_line_id';
         public $foreignKey='enquiry_hdr_id';
	public $fillable =['enquiry_line_id','enquiry_hdr_id','product_id','product_description','uom_code_id','qty','promised_date'];
}

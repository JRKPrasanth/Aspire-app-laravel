<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class documentinterntransferlines extends Model
{
   protected $table ='a_doctransfer_lines_t';
	protected $primaryKey ='doc_line_id';
         protected $foreignKey='doc_hdr_id';
	protected $fillable=['doc_category','doc_type','reference_no','vendor_name','no_of_copies','comments','created_at','created_by','updated_at','updated_by','company_id','location_id'];
}





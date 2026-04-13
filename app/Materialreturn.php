<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialreturn extends Model
{
    protected $table = 'w_material_return_hdr';
    protected $primaryKey ='material_return_hdr_id';
    protected $fillable =['material_return_hdr_id','qa_submitstage_trx_hdr_id','product_id','job_no','batch_no','created_by','company_id','location_id','organization_id','created_at','updated_at','last_updated_by'];

	
}

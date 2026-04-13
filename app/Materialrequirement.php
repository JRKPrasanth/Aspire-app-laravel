<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialrequirement extends Model
{
    protected $table='qc_materialreq_hdr_t';
  	protected $primaryKey='qc_material_req_id';
  	protected $fillable = ['batch_no','employee_id','start_date','end_date','save_status','source_type','remarks','created_by','last_updated_by','created_at','updated_at','organization_id','company_id','location_id','active'];
}

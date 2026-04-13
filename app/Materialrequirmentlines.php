<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialrequirmentlines extends Model
{
    protected $table='qc_materialreq_lines_t';
  	protected $primaryKey='qc_materialreq_line_id';
  	public $foreignKey='qc_material_req_id';
	protected $fillable = ['line_no','product_id','uom_code_id', 'comments','issue_qty','component_qoh','created_by','last_updated_by','created_at','updated_at','organization_id','company_id','location_id'];
}

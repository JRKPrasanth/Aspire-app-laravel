<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class replacementapprovallines extends Model
{
       protected $table="p_replacement_lines_t";
    protected $primaryKey="replacement_line_id";
    public $foreignKey='replacement_hdr_id';
	protected $fillable = [
        'line_no','product_id','uom_code_id', 'qty','comments','location_id','company_id','organization_id','created_by','created_at','last_updated_by','updated_at'
    ];
}

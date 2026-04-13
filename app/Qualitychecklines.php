<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualitychecklines extends Model
{
    
    protected $table='i_quality_spec_trx_lines_t';
    protected $primaryKey='quality_spec_trx_line_id';
    protected $foreignKey='quality_spec_trx_hdr_id';
    protected $fillable=['parameter','standard','measurement','accepted_qty','rejected_qty'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

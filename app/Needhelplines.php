<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Needhelplines extends Model
{
    protected $table='a_sop_line_t';
    protected $primaryKey='sop_line_id ';
    protected $foreignKey='sop_id ';
    protected $fillable =['sop_line_id','sop_id','activities','active'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

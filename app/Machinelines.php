<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machinelines extends Model
{
    protected $table='w_machine_lines_t';
    protected $primaryKey='machine_line_id';
    protected $foreignKey='machine_hdr_id';
	protected $fillable =['line_no','product_type_id','capacity','comments'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

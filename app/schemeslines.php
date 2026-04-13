<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class schemeslines extends Model
{
    public $table='s_schemes_lines_t';
	public $primaryKey='schemes_lines_id';
	public $foreignKey='schemes_hdr_id';	
	public $fillable =['line_no','product_id','line_pdt_id','line_pdt_qty','scheme_base','schemes_type','gift_product_id','scheme_base_value_from','scheme_base_value_to','schemes_type_value','comments'];

public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }

      public function headerdata()
    {
        return $this->hasOne('App\Schemes','schemes_hdr_id','id');
    } 

}

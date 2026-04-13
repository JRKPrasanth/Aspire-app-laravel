<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schemes extends Model
{
   public $table='s_schemes_hdr_t';
	public $primaryKey='schemes_hdr_id';
		
	public $fillable =['schemes_name','scheme_type','start_date','end_date','location','savestatus','approver_id'];
public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }

      public function linesdata()
    {
        return $this->hasOne('App\schemeslines');
    } 

}

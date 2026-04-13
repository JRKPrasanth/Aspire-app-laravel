<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class agency extends Model
{
    protected $table='ma_agency_t';
    protected $primaryKey='agency_id';
    protected $fillable=['agency_id','agency_name','email','mobile_no','address','country','state','city'];
    public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }

}

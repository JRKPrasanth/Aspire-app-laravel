<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lookuphdr extends Model
{
 protected $table='a_lookup_hdr_t';
    protected $primaryKey='lookuphdr_id';
	protected $fillable =['lookuphdr_id','lookup_menu','lookup_submenu','lookup_type','description'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }

}

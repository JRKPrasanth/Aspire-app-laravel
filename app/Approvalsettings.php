<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approvalsettings extends Model
{
    protected $table='m_approvalsettings_hdr_t';
    protected $primaryKey='approvalsettings_hdr_id';
    protected $fillable=['module_name'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

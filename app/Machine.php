<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $table='w_machine_hdr_t';
    protected $primaryKey='machine_hdr_id';
	protected $fillable =['machine_code','machine_name','remarks','assigned_to','electricity_cost','capacity','department_id','locationid','relocated_date','purchased_date','machine_make','cost','files','vendor_id','amc_vendor_id','from_date','to_date','renewal_date','file_name','asset_code','department_name','critical','active','last_updated_by'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

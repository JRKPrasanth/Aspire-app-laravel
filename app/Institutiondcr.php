<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Institutiondcr extends Model
{
    protected $table='app_institution_dcr';
    protected $primaryKey='institution_dcr_id';
	protected $fillable =['institution_id','area','keycontact1','focus_product','timing','activity_id','visit_with','outcome_id','institution_raminder_reason','organization_id','company_id','location_id','created_by','last_updated_by','created_at','updated_at'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stockist extends Model
{
    protected $table ='app_stockist_t';
    protected $primaryKey ='stockist_id';
    protected $fillable=['doctor_id','stockist_name','chemist_id','contact_person','stockist_phone','active','created_at','created_by','updated_at','last_updated_by','location_id','organization_id','company_id'];

	public function getTableColumns() 
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
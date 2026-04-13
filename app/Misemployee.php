<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Misemployee extends Model
{
    protected $table = 'sd_mar_employee_detail_t';
    protected $primaryKey = 'id';
    protected $fillable = ['employee_name', 'zone', 'region', 'state', 'hq_name','desigination','company_id','location_id','created_by','created_at','last_updated_by','updated_at','active'];
    public function getTableColumns() 
    {
       return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companylines extends Model
{
    protected $table='m_company_line_t';
    protected $primaryKey='company_line_id';
    protected $foreignKey='company_id';
    protected $fillable =['company_line_id','company_id','location_id','description'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

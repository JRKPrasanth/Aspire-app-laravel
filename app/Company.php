<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table='m_company_t';
    protected $primaryKey='company_id';
    protected $fillable =['company_name','company_code','website_address','default_org','gst_no','contact_no','active','tax_reg_no','excise_registration_no','cin_no','email_id','gst_no','pan_no'];
    public function getTableColumns() 
    {
       return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}

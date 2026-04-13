<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Createuser extends Model
{
    protected $table='a_m_users_t';
    protected $primaryKey='user_id';
	protected $fillable =['employee_number','user_id','group_id','username','first_name','active','last_name','email','mobile_no','avatar','loc_id','comp_id','active','user_mail','user_password'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountsettings extends Model
{
      protected $table='hr_attendance_setting_t';
    protected $primaryKey='attendance_id';
    protected $fillable =['attendance_id','department_id','ot_formula','min_ot','max_ot','gt_for_late_coming','gt_for_early_going','attendance_rules','rules_data'];
    public function getTableColumns() 
    {
       return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}

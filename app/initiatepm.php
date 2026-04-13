<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class initiatepm extends Model
{
    protected $table='machine_pm_detail_t';
    protected $primaryKey='initiate_pm_id';
    protected $fillable=['initiate_pm_id','pm_no','machine_id','department_id','actual_pm_date','initiate_date','user_clearance_by'];
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}

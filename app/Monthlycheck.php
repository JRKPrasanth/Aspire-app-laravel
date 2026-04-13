<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Monthlycheck extends Model
{
    protected $table='pm_monthly_checking_tbl';
    protected $primaryKey='pm_checking_id';
    protected $fillable=['initiate_pm_id','machine_id','department_id','checklist','observation','status','remarks'];
     public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}

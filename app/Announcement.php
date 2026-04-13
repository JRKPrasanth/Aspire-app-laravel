<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'hr_announcement_t';
    protected $primaryKey = 'id';
    protected $fillable = ['ann_name','start_date',      'end_date','attachment','active','company_id','organization_id','location_id','updated_at','created_at','created_by','last_updated_by'];
    
    public function getTableColumns() 
    {
       return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Needhelp extends Model
{
    protected $table = 'a_sop_t';
    protected $primaryKey = 'sop_id';
    protected $fillable = ['primary_menu', 'submenu', 'menu', 'url', 'created_by'];
    public function getTableColumns() 
    {
       return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    
}

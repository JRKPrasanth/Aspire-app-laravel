<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumablelines extends Model
{
     protected $table='i_consumable_lines_t';
    protected $primaryKey='consumable_line_id';
    protected $foreignKey='consumable_hdr_id';
	protected $fillable =['consumable_hdr_id','subinventory','sublocator','product_id','qty','accounts_structure_id','line_no','created_by','created_at','last_updated_by','updated_at','organization_id','company_id','location_id'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

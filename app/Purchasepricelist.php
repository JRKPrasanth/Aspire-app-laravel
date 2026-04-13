<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchasepricelist extends Model
{
	protected $table ='i_pricelist_hdr_t';
	protected $primaryKey ='pricelist_hdr_id';
	public $foreignKey='';
	protected $fillable=['pricelist_hdr_id','pricelist_name','price_list_type','description','active','start_date','end_date','edit_id'];
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

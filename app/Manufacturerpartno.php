<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturerpartno extends Model
{
  protected $table='m_products_t';
  protected $primaryKey='product_id';
	public $foreignKey='';	
protected $fillable = [
      'product_id','product_group_id','remarks'
  ];
	
		public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

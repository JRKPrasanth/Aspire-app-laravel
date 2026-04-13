<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturerpartnolines extends Model
{
     protected $table='m_manufacturer_partno_t';
  protected $primaryKey='manufacturer_partno_id';
	public $foreignKey='product_id';
protected $fillable = [
      'product_id','manufacturer_partno_id','manufacturer_source','manufacturer_source_value_id','part_no','part_no_description',
  ];
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

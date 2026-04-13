<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchasepricelistlines extends Model
{
  protected $table ='i_pricelist_lines_t';

  protected $primaryKey ='pricelist_line_id';
	public $foreignKey='pricelist_hdr_id';
  protected $fillable=['pricelist_hdr_id','product_id','unit_price','std_price','comments'];
public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

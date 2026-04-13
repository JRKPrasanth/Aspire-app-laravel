<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesreplacementlines extends Model
{
    protected $table='s_replacement_lines_t';
      protected $primaryKey='replacement_line_id';
		public $foreignKey='replacement_hdr_id';
      protected $fillable = ['tax_excemption','hsn_code','invoice_hdr_id','line_no','product_id','product_description','uom_code_id',
      'salesorder_qty','qty','unit_price','discount_percentage','discount_amount','line_sub_total','tax_group_id',
      'tax_amount','line_total','comments','invoice_qty','replacement'
      ];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
	
	
}

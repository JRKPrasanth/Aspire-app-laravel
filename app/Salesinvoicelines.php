<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesinvoicelines extends Model
{
    protected $table='s_invoice_lines_t';
      protected $primaryKey='invoice_line_id';
		public $foreignKey='invoice_hdr_id';
      protected $fillable = ['invoice_line_id','tax_excemption','hsn_code','invoice_hdr_id','line_no','product_id','product_description','uom_code_id',
      'salesorder_qty','qty','unit_price','discount_percentage','discount_amount','line_sub_total','tax_group_id',
      'tax_amount','line_total','comments','invoice_qty','std_price'
      ];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
	
	
}

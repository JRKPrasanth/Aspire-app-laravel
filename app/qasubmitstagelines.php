<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class qasubmitstagelines extends Model
{
    protected $table='w_qa_submitstage_line_t';
    protected $primaryKey='w_qa_submitstage_trx_line_id';
    protected $foreignKey='qa_submitstage_trx_hdr_id';
    protected $fillable=['w_qa_submitstage_trx_line_id','product_id','uom_code_id','production_qty'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

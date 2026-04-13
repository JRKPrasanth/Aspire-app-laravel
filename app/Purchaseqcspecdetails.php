<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseqcspecdetails extends Model
{
   protected $table = 'p_quality_spec_trx_lines_t';
   public $primaryKey='quality_spec_trx_id';
   public $foreignKey='reference_source_hdr_id';
   public $fillable =['quality_spec_trx_id','product_id','parameter','spec_criteria','spec_value_from','spec_value_to',
            'measurement','reference_source','reference_source_hdr_id',"line_no",'quality_status','remarks'];
}

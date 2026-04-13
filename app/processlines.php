<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class processlines extends Model
{
  protected $table='wire_drawing_tbl';
  protected $primaryKey='wire_id';
  protected $foreignKey='process_hdr_id';
  protected $fillable =['wire_id','process_hdr_id','process','line_no','machine_id','output_size','seq_no','planed_qty','palned_time','packing','customer','achived_qty','actual_time','remarks'];
 public function getTableColumns()
 {
      return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
   }
}

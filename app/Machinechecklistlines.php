<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machinechecklistlines extends Model{
   protected $table='checklist_lines_tbl';
   public $foreignKey='checklist_hrd_id';
   protected $primaryKey='checklist_lines_id';
   protected $fillable =['checklist_id'];
	
}

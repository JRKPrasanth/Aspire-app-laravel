<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrnLines extends Model
{
 protected $table = 'p_grn_lines_t';
 public $primaryKey='grn_line_id';
public $foreignKey='grn_id';
 protected $guarded = [];     
}

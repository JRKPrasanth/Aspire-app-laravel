<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subinventorytransferlines extends Model
{
    public $table='i_subinventory_transfer_lines_t';
	public $primaryKey='subinventory_transfer_line_id';
    public $foreignKey='subinventory_transfer_hdr_id';
    protected $guarded = []; 
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rptdisplayelementslines extends Model
{
    protected $table='a_rpt_displayelements_lines_t';
    protected $primaryKey='rpt_displayelements_line_id';
    protected $foreignKey='rpt_displayelements_hdr_id';
    protected $fillable=['element_name','element_content'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoicefrompickorder extends Model
{
    protected $table='s_pickrelease_hdr_t';
    protected $PrimaryKey='so_pickrelease_hdr_id';
    protected $fillable =['release_reference_no','release_source','release_date'];
}

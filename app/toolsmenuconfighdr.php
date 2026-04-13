<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class toolsmenuconfighdr extends Model
{
    protected $table='m_toolmenus_listing_hdr_t';
    protected $primaryKey='m_toolmenus_listing_hdr_id';
    protected $fillable=[
  'm_toolmenus_listing_hdr_id','pagemodule','description'

  ];
}

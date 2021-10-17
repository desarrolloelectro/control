<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DsiDataProduct extends Model
{
    public $timestamps = false;
    protected $table = 'dsi_data_products';
    public function dsi_data_advances(){
        return $this->hasMany(DsiDataAdvance::class);
    }
}

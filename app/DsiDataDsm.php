<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DsiDataDsm extends Model
{
    public $timestamps = false;
    protected $table = 'dsi_data_dsm';
    
    public function dsi_data_products(){
        $this->dsi_data_products = DsiDataProduct::where('dsi_data_dsm_id',$this->id)->get();
        //return $this->hasMany(DsiDataProduct::class);
    }

}
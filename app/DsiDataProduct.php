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
    public function dsi_data_all_advances()
    {
        return $this->belongsToMany('App\DsiDataAdvance')
            ->withPivot('id','value','state')
            ->withTimestamps()
            ->where('dsi_data_advance_dsi_data_product.state',1);
    }
}

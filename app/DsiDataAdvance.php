<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DsiDataAdvance extends Model
{
    public $timestamps = true;
    protected $table = 'dsi_data_advances';
    
    public function getSaldoAttribute()
    {
        $this->dsi_data_all_products();
        //;
        //var_dump($this->dsi_data_all_products);
        $todo = 0;
        foreach ($this->dsi_data_all_products as $avs){
            $todo += $avs->pivot->value;
        }
        $saldo = $this->valor_recibo - $todo;
        return $saldo;
        
    }
    public function dsi_data_all_products()
    {
        return $this->belongsToMany('App\DsiDataProduct')->withPivot('value','state')->withTimestamps();
    }
}

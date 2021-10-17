<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Informe_venta extends Model
{
    public $timestamps = false;
    protected $table = 'informe_ventas';

    public function medio_pago(){
        return $this->belongsTo(Medio_pago::class,'mediopago','id');
    }
    
}

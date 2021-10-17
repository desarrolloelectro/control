<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    public $timestamps = false;
    protected $table = 'bancos';

    public function tipo_pago(){
        return $this->belongsTo(Tipo_pago::class,'tipo_pago_id','id');
    }

    public function tipo_cuenta($tipo_cuenta_id){
        $nombre = "NO DEFINE";
        if($tipo_cuenta_id == '1'){$nombre = 'AHORROS';}
        if($tipo_cuenta_id == '2'){$nombre = 'CORRIENTE';}

        return $nombre;
    }
    
}

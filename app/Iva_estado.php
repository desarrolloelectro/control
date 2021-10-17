<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iva_estado extends Model
{
    public $timestamps = false;
    protected $table = 'iva_estados';

    public function tipo_nombre($tipo){
        $nombre = 'NO DEFINE';
        if($tipo == 1){
            $nombre = 'ESTADO CAJAS 1';
        }
        if($tipo == 2){
            $nombre = 'ESTADO BANCOS';
        }
        if($tipo == 3){
            $nombre = 'ESTADO CAJAS 2';
        }

        return $nombre;
    }
    
}

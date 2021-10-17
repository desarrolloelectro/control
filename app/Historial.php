<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    public $timestamps = false;
    protected $table = 'historiales';

    public function usuario_nombre($id){
        $nombre = "";
        $usuario = Usuario::where('coduser',$id)->first();
        if($usuario != null){
            $nombre = $usuario->nombre;
        }
        return $nombre;
    }

    public function estado_nombre($estado_id){

        $nombre = '';
        $estado = Iva_estado::where('id',$estado_id)->first();
        if($estado != null){
            $nombre = $estado->nombre;
        }

        return $nombre;
    }


    
}

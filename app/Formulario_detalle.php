<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formulario_detalle extends Model
{
    public $timestamps = false;
    protected $table = "formularios_detalle";

    public function tipo_pregunta_nombre($tipo_pregunta){
        $tipo = "";
        if($tipo_pregunta == 1){
            $tipo = "SI / NO";
        }elseif($tipo_pregunta == 2){
            $tipo = "ABIERTA";
        }elseif($tipo_pregunta == 3){
            $tipo = "NUMÉRICA";
        }elseif($tipo_pregunta == 4){
            $tipo = "FECHA";
        }
        return $tipo;
    }
    
}

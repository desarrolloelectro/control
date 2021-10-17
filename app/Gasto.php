<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    public $timestamps = false;
    protected $table = 'gastos';

    public function agencia(){
        return $this->belongsTo(Agencia::class,'agencia_id','codagen');
    }

    public function tipo_gasto(){
        return $this->belongsTo(Tipo_gasto::class,'tipo_gasto_id','id');
    }

    public function tipo_doc_auditoria(){
        return $this->belongsTo(Tipo_gasto::class,'tipo_doc_audi','id');
    }

    public function estado(){
        return $this->belongsTo(Gasto_estado::class,'estado_id','id');
    }

    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function tipo_identificacion(){
        return $this->belongsTo(Tipo_identificacion::class,'tipo_identificacion_id','id');
    }
    public function banco(){
        return $this->belongsTo(Banco::class,'banco_id','id');
    }
    public function tipo_pago(){
        return $this->belongsTo(Tipo_pago::class,'tipo_pago_id','id');
    }

    /**public function tipo_pago($tipo_pago_id){
        $nombre = "NO DEFINE";
        if($tipo_pago_id == '1'){$nombre = 'CAJA';}
        if($tipo_pago_id == '2'){$nombre = 'BANCO';}

        return $nombre;
    }**/

    public function usuario_nombre($id){
        $nombre = "";
        $usuario = Usuario::where('coduser',$id)->first();
        if($usuario != null){
            $nombre = $usuario->nombre;
        }
        return $nombre;
    }
    
}

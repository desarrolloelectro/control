<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    public $timestamps = false;
    protected $table = 'cotizaciones';

    public function agencia(){
        return $this->belongsTo(Agencia::class,'agencia_id','codagen');
    }

    public function tipo_gasto(){
        return $this->belongsTo(Tipo_gasto::class,'tipo_gasto_id','id');
    }

    public function estado(){
        return $this->belongsTo(Cotizacion_estado::class,'estado_id','id');
    }

    public function gasto_estado(){
        return $this->belongsTo(Gasto_estado::class,'gasto_estado_id','id');
    }

    public function revisoria_estado(){
        return $this->belongsTo(Revisoria_estado::class,'revisoria_estado_id','id');
    }

    public function tipo_gasto_gasto(){
        return $this->belongsTo(Tipo_gasto::class,'tipo_gasto_id_gasto','id');
    }

    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function banco(){
        return $this->belongsTo(Banco::class,'banco_id','id');
    }

    public function tipo_pago(){
        return $this->belongsTo(Tipo_pago::class,'tipo_pago_id','id');
    }

    public function valor_cotizaciones($id){
        $valor = "";
        $cotizacion_detalle = Cotizacion_detalle::where('cotizacion_id',$id)->get();
        foreach ($cotizacion_detalle as $fila) {
            $valor = $valor.", ".$fila->valor;
            
        }
        return $valor;
    }

    public function valor_autorizado($id){
        $valor = 0;
        $cotizacion_detalle = Cotizacion_detalle::where('cotizacion_id',$id)->where('autorizado',1)->first();
        if($cotizacion_detalle != null){
            $valor = $cotizacion_detalle->valor;
        }
        return $valor;
    }

    public function usuario_nombre($id){
        $nombre = "";
        $usuario = Usuario::where('coduser',$id)->first();
        if($usuario != null){
            $nombre = $usuario->nombre;
        }
        return $nombre;
    }
    
}

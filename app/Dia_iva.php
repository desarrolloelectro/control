<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dia_iva extends Model
{
    public $timestamps = false;
    protected $table = 'dia_ivas';

    public function medio_pago(){
        return $this->belongsTo(Medio_pago::class,'mediopago','id');
    }

    public function tipo_identificacion(){
        return $this->belongsTo(Tipo_identificacion::class,'tipoid','id');
    }

    public function tipo_factura(){
        return $this->belongsTo(Tipo_factura::class,'tipofac','id');
    }

    public function tipo_documento(){
        return $this->belongsTo(Tipo_documento::class,'tipodoc','id');
    }

    public function categoria_nombre(){
        return $this->belongsTo(Categoria::class,'categoria','id');
    }

    public function genero_nombre(){
        return $this->belongsTo(Genero::class,'genero','id');
    }

    public function unidad_nombre(){
        return $this->belongsTo(Unidad::class,'unidad','id');
    }

    public function iva_estado(){
        return $this->belongsTo(Iva_estado::class,'estado_id','id');
    }

    public function banco_estado(){
        return $this->belongsTo(Iva_estado::class,'banco_estado_id','id');
    }

    public function caja2_estado(){
        return $this->belongsTo(Iva_estado::class,'caja2_estado_id','id');
    }
    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dsi extends Model
{
    public $timestamps = false;
    protected $table = 'dsi';
    public static $meta = false;
    public static $depurar = false;
    public static $fields_data = [
        "id" => "ID",//'id'
        "type" => "Tipo de Venta",//'tipoid'
        "tipoid" => "Tipo de identificación",//'tipoid'
        "identificacion" => "Identificación",//'identificacion'
        "nombre" => "Nombre",//'nombre'
        "tipofac" => "Tipo de factura",//'tipofac'
        "tipodoc" => "Tipo de documento",//'tipodoc'
        "ciudad" => "Ciudad",//'tipodoc'
        "depto" => "Departamento",//'tipodoc'
        "numdoc" => "Número de documento",//'numdoc'
        "lugar" => "Lugar",//'lugar'
        "fecha" => "Fecha",//'fecha'
        "categoria" => "Categoría",//'categoria'
        "genero" => "Genero",//'genero'
        "cantidad" => "Cantidad",//'cantidad'
        "unidad" => "Unidad",//'unidad'
        "descripcion" => "Descripción",//'descripcion'
        "vrunit" => "Valor unitario",//'vrunit'
        "vrtotal" => "Valor total",//'vrtotal'
        "mediopago" => "Medio de pago",//'mediopago'
        "numsoporte" => "Número de soporte",//'numsoporte'
        "fechaentrega" => "Fecha de entrega",//'fechaentrega'
        "pvppublico" => "PVP Público Principal",//'pvppublico'
        "obs" => "Observaciones",//'obs'
        "date_new" => "Fecha de creación por Usuario",//'date_new'
        "created_at" => "Fecha y Hora de creación por Sistema",//'created_at'
        "user_new" => "Creado por",//'user_new'
        "user_update" => "Actualizado por",//'user_update'
        "updated_at" => "Fecha de actualización",//'updated_at'
        "factura" => "Factura",//'factura'
        "estado_id" => "Estado Caja",//'estado_id'
        "banco_estado_id" => "Estado Bancos",//'banco_estado_id'
        "caja2_estado_id" => "Estado Caja 2",//'caja2_estado_id'
        "urlimagen" => "Archivo",//'urlimagen'
    ];
    public function permiso(){
        return $this->belongsTo(Permiso::class,'permission','codigo');
    }
    public function histories(){
        return $this->hasMany(DsiAudit::class,'context_id','id')->where('context', '=', 'dsi');
    }
    public function metas(){
        return $this->hasMany(DsiMeta::class)->where('parent','=','');
    }
    function states(){
        if($this->state == 0){
            return "Inactivo";
        }else if($this->state == 1){
            return "Activo";
        }
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DsiData extends Model
{
    public $timestamps = false;
    protected $table = 'dsi_data';
    
        
    public function getCiudadAttribute()
    {
        return $this->tipodoc != null ? !empty($this->tipo_documento) ? $this->tipo_documento->codciu . " :: " . $this->tipo_documento->ciudad : 'NO DEFINE' : 'NO DEFINE';;
    }
    public function getDeptoAttribute()
    {
        return $this->tipodoc != null ? !empty($this->tipo_documento) ? $this->tipo_documento->coddpto . " :: " . $this->tipo_documento->depto : 'NO DEFINE' : 'NO DEFINE';
    }
    
    public function getLugarAttribute()
    {
    return $this->tipo_documento != null ? $this->tipo_documento->codciu." :: ".$this->tipo_documento->ciudad." :: ".$this->tipo_documento->coddpto." :: ".$this->tipo_documento->depto : 'NO DEFINE';
    }

    public function getVrtotalFtAttribute()
    {
        return "$".number_format($this->vrtotal,1,",",".");
    }
    public function getVrtotalFrtAttribute()
    {
        return $this->vrtotal;
    }
    public function getVrunitFtAttribute()
    {
        return "$".number_format($this->vrunit,1,",",".");
    }
    public function getVrunitFrtAttribute()
    {
        return $this->vrunit;
    }
    public function getPvppublicoFtAttribute()
    {
        return "$".number_format($this->pvppublico,1,",",".");
    }
    public function getPvppublicoFrtAttribute()
    {
        return $this->pvppublico;
    }
    public function getFechaFtAttribute()
    {
        return custom_date_format($this->fecha, "d/m/Y");
    }
    public function getDateNewFtAttribute()
    {
        return custom_date_format($this->date_new, "d/m/Y");
    }
    public function getCreatedAtFtAttribute()
    {
        return custom_date_format($this->created_at);
    }
    public function getUpdatedAtFtAttribute()
    {
        return custom_date_format($this->updated_at);
    }
    public function getTipofacFtAttribute()
    {
        return $this->tipofac != null ? $this->tipo_factura->nombre : "NO DEFINE";
    }
    public function getCategoriaFtAttribute()
    {
        return $this->categoria != null ? $this->categoria_nombre->nombre : 'NO DEFINE';
    }
    public function getTipoidFtAttribute()
    {
        return $this->tipoid != null ? !empty($this->tipo_identificacion) ? $this->tipo_identificacion->abreviatura ." :: ". $this->tipo_identificacion->nombre : $this->$field_view : "NO DEFINE";
    }
    public function getGeneroFtAttribute()
    {
        return  $this->genero != null ? $this->genero_nombre->nombre : 'NO DEFINE';
    }
    public function getMediopagoFtAttribute()
    {
        return $this->medio_pago != null ? $this->medio_pago->nombre : 'NO DEFINE' ;
    } 
    public function getTipodocFtAttribute()
    {
        return $this->tipodoc != null ? !empty($this->tipo_documento) ? $this->tipo_documento->codigo : $this->tipodoc : 'NO DEFINE';
    }
       
    public function getEstadoIdFtAttribute()
    {
        $estado = $this->estado_id != null ? $this->iva_estado->nombre : '';
        return $estado;
    }
    public function getEstadoIdFstAttribute()
    {
        $color = $this->estado_id != null ? $this->iva_estado->color : '';
        $estado = $this->estado_id != null ? $this->iva_estado->nombre : '';
        return '<span class="span-estilo" style="background:'.$color.';">'.$estado.'</span>';
    }

    public function getBancoEstadoIdFtAttribute()
    {
        $estado = $this->banco_estado_id != null ? $this->banco_estado->nombre : '';
        return $estado;
    }
    public function getBancoEstadoIdFstAttribute()
    {
        $color = $this->banco_estado_id != null ? $this->banco_estado->color : '';
        $estado = $this->banco_estado_id != null ? $this->banco_estado->nombre : '';
        return '<span class="span-estilo" style="background:'.$color.';">'.$estado.'</span>';
    }                      
    public function getCaja2EstadoIdFtAttribute()
    {
        $estado = $this->caja2_estado_id != null ? $this->banco_estado->nombre : '';
        return $estado;
    }
    public function getCaja2EstadoIdFstAttribute()
    {
        $color = $this->caja2_estado_id != null ? $this->banco_estado->color : '';
        $estado = $this->caja2_estado_id != null ? $this->banco_estado->nombre : '';
        return '<span class="span-estilo" style = "background: '. $color .';">'. $estado .'</span>';
    }
    public function getUrlimagenFstAttribute()
    {
        if($this->urlimagen != null && $this->urlimagen != ''){
            $image = '<a class="btn2 btn-dark" target = "_blank" href="'. asset('uploads/archivos') .'/'.$this->urlimagen.'" class = "bold"><i class = "fa fa-paperclip"></i></a>';
        }else{
            $image = '';
        }
        return $image;
    }
    public function getContextIdAttribute()
    {
        return '["'.$this->id.'","'.$this->dsi_id.'"]';
    }
    public function dsi(){
        return $this->belongsTo(Dsi::class,'dsi_id','id');
    }

    public function dsi_data_advances(){
        return $this->hasMany(DsiDataAdvance::class);
    }
    public function dsi_data_dsms(){
        return $this->hasMany(DsiDataDsm::class);
    }

    public function dsi_metas(){
        return $this->hasMany(DsiMeta::class,'dsi_id','dsi_id');
    }
    public function dsi_meta_values(){
        return $this->hasMany(DsiMetaValues::class,'dsi_data_id','id')->where('dsi_id', $this->dsi_id);
    }
    public function dsi_meta_value($dsi_meta_id){
        $data = DsiMetaValues::where('dsi_data_id',$this->id)
                ->where('dsi_meta_id','=', $dsi_meta_id)
                ->firstOrNew();
        $this->dsi_meta_value = $data;
    }

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
    public function histories(){
        return $this->hasMany(DsiAudit::class,'context_id2','id')
                ->where('context_id', '=', $this->dsi_id)
                ->where('context', '=', 'dsi_data');
    }
}
/*
    protected $attributes = array(
        "id",
        "tipoid",
        "identificacion",
        "nombre_data",
        "tipofac",
        "tipodoc",
        "tipodoc.ciudad",
        "tipodoc.depto",
        "numdoc",
        "lugar",
        "fecha",
        "categoria",
        "genero",
        "cantidad",
        "unidad",
        "descripcion",
        "vrunit",
        "vrtotal",
        "mediopago",
        "numsoporte",
        "fechaentrega",
        "pvppublico",
        "obs",
        "date_new",
        "created_at",
        "user_new",
        "user_update",
        "updated_at",
        "factura",
        "estado_id",
        "banco_estado_id",
        "caja2_estado_id",
        "urlimagen"
    );
    */
    
    //`id`, `tipoid`, `identificacion`, `nombre`, `tipofac`, `tipodoc`, `numdoc`, `lugar`, `fecha`, `categoria`, `genero`, `cantidad`, `unidad`, `descripcion`, `vrunit`, `vrtotal`, `mediopago`, `numsoporte`, `fechaentrega`, `pvppublico`, `obs`, `date_new`, `user_new`, `user_update`, `created_at`, `updated_at`, `factura`, `estado_id`, `banco_estado_id`, `caja2_estado_id`, `urlimagen`, `dsi_id` 
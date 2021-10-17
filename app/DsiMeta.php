<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DsiMeta extends Model
{
    public $timestamps = false;
    protected $table = 'dsi_meta';
    public static  $types = [
        'number' => 'Número', 
        'currency' => 'Moneda', 
        'text' => 'Texto', 
        'textarea' => 'Área de Texto', 
        'list' => 'Lista', 
        'date' => 'Fecha', 
        'time' => 'Hora', 
        'datetime' => 'Fecha y Hora', 
        'file' => 'Archivo'
    ];
    public function dsi(){
        return $this->belongsTo(Dsi::class,'dsi','id');
    }
    public function childs(){
        return $this->hasMany(DsiMeta::class,'parent', 'id');
    }
    public function dsi_meta_value($dsi_data_id){
        return $this->hasOne(DsiMetaValues::class,'dsi_meta_id','id')->where('dsi_data_id',$dsi_data_id);
    }
    /*
    public function dsi_metas(){
        return $this->hasMany(DsiMeta::class,'dsi_meta','id');
    }
    */
}

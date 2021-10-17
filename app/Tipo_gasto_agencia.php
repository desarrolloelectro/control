<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipo_gasto_agencia extends Model
{
    protected $table = 'tipo_gasto_agencias';
    public $timestamps = false;

    public function tipo_gasto(){
        return $this->belongsTo(Tipo_gasto::class,'tipo_gasto_id','id');
    }
}

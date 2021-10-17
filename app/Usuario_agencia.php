<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario_agencia extends Model
{
    protected $table = 'usuario_agencias';
    public $timestamps = false;

    public function usuario(){
        return $this->belongsTo(Usuario::class,'usuario_id','coduser');
    }
}

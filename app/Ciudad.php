<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    public $timestamps = false;
    protected $table = 'ciudades';

    public function departamento(){
        return $this->belongsTo(Departamento::class,'departamento','id');
    }
}

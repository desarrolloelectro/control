<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    protected $connection = 'toolset_perf';
    protected $table = 'agencia';
    protected $primaryKey = 'codagen';
    public $timestamps = false;
}

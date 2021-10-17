<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DsiMetaValues extends Model
{
    public $timestamps = false;
    protected $table = 'dsi_meta_values';
    
    public function dsi_meta(){
        return $this->belongsTo(DsiMeta::class,'dsi_meta_id','id');
    }
    public function dsi(){
        return $this->belongsTo(Dsi::class,'dsi_id','id');
    }

}

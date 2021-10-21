<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DsiPermission extends Model
{
    public $timestamps = false;
    protected $table = 'dsi_permissions';
    public static $fields_data = [
        "id" => "ID",//'id'
        "dsi_id" => "Dsi ID",//'id'
        "slug" => "Slug",//'slug'
        "permission" => "Permiso",//'permission'
    ];
    public static function dsi_permiso($dsi_id,$slug){
        $data = DsiPermission::where('slug',$slug)->where('dsi_id',$dsi_id)->firstOrNew();
        return $data->permission;
    }
}

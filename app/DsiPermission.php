<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DsiPermission extends Model
{
    public $timestamps = false;
    protected $table = 'dsi_permissions';
    public static $fields_data = [
        "id" => "ID",//'id'
        "slug" => "Slug",//'slug'
        "permission" => "Permiso",//'permission'
    ];
    public static function dsi_permiso($slug){
        $data = DsiPermission::where('slug',$slug)->firstOrNew();
        return $data->permission;
    }
}

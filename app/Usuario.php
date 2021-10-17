<?php

namespace App;
use Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use Notifiable;
    public $incrementing = false;
    public $timestamps = false;
    protected $connection = 'toolset_perf';
    protected $table = 'usuarios';
    protected $primaryKey = 'coduser';
    public $remember_token=false;

    public function agencia_nombre(){
        return $this->belongsTo(Agencia::class,'agencia','codagen');
    }
  
    public function nivel_mostrar($id){
        $nivel = "SIN PERFIL";
        $rol = Rol::where('id',$id)->first();
        if($rol != null){
            $nivel  = $rol->nombre;
        }
        return $nivel;

    }

    public function nivel_detalle($id,$sistema){
        $nivel = "SIN PERFIL";

        if($sistema == 'control'){
            $rol = Rol::where('id',$id)->first();
            if($rol != null){
                $nivel  = $rol->nombre;
            }
        }

        if($sistema == 'perfilaciones'){
            $rol = DB::table('toolset_perf.roles')->where('codrol',$id)->first();
            if($rol != null){
                $nivel  = $rol->nomrol;
            }
        }

        if($sistema == 'clientes'){
            $rol = DB::table('toolset_clie.roles')->where('id',$id)->first();
            if($rol != null){
                $nivel  = $rol->nombre;
            }
        }

        if($sistema == 'juridico'){
            $rol = DB::table('toolset_jurid.roles')->where('id',$id)->first();
            if($rol != null){
                $nivel  = $rol->nombre;
            }
        }

        if($sistema == 'importaciones'){
            $rol = DB::table('toolset_import.roles')->where('id',$id)->first();
            if($rol != null){
                $nivel  = $rol->nombre;
            }
        }

        if($sistema == 'interelec'){
            $rol = DB::table('toolset_inter.roles')->where('id',$id)->first();
            if($rol != null){
                $nivel  = $rol->nombre;
            }
        }

        if($sistema == 'auditoria'){
            $rol = DB::table('toolset_audi.roles')->where('id',$id)->first();
            if($rol != null){
                $nivel  = $rol->nombre;
            }
        }
        
        if($sistema == 'formularios'){

            if($id == 0){ $nivel = 'INACTIVO'; }
            if($id == 1){ $nivel = 'ADMINISTRADOR'; }
            if($id == 2){ $nivel = 'CONSULTA'; }
            
        }

        if($sistema == 'repuestos'){

            if($id == 0){ $nivel = 'INACTIVO'; }
            if($id == 1){ $nivel = 'ADMINISTRADOR'; }
            if($id == 5){ $nivel = 'EDICIÓN'; }
            if($id == 6){ $nivel = 'CONSULTA'; }
            if($id == 7){ $nivel = 'DISTRIBUIDOR'; }
            
        }

        if($sistema == 'cotiza'){

            if($id == 0){ $nivel = 'INACTIVO'; }
            if($id == 1){ $nivel = 'ADMINISTRADOR'; }
            if($id == 2){ $nivel = 'CONSULTA'; }
            if($id == 3){ $nivel = 'USUARIO'; }
            
        }

        if($sistema == 'helpdesk'){

            if($id == 0){ $nivel = 'INACTIVO'; }
            if($id == 1){ $nivel = 'ADMINISTRADOR'; }
            if($id == 2){ $nivel = 'SOPORTE'; }
            if($id == 3){ $nivel = 'USUARIO'; }
            if($id == 4){ $nivel = 'CONSULTA'; }
            
        }

        if($sistema == 'cartera'){

            if($id == 0){ $nivel = 'SIN PERFIL'; }
            if($id == 1){ $nivel = 'ADMINISTRADOR'; }
            if($id == 4){ $nivel = 'APOYO'; }
            if($id == 5){ $nivel = 'COBRADOR'; }
            if($id == 6){ $nivel = 'CONSULTA'; }
            if($id == 7){ $nivel = 'OCM - PRUEBA'; }
            
        }
        
        return $nivel;

    }

    public function agencia_detalle($agencia_id){
        $nombre = $agencia_id;
        $agencia = Agencia::where('codagen',$agencia_id)->first();
        if($agencia != null){
            $nombre = $agencia->agennom;
        }
        return $nombre;
    }

    public function validar_permiso($permiso){
        $rol = Rol::where('id',Auth::user()->nivel_control)->firstOrFail();
        $permisos = $rol->permisos;
        $continuar = false;

        $porciones = explode(",", $permisos);
        if(in_array($permiso,$porciones)){
            $continuar = true;
        }else{
            $continuar = false;
        }
        return $continuar;
    }
    
}


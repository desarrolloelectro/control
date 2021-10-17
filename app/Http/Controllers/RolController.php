<?php

namespace App\Http\Controllers;
use App\Rol;
use App\Modulo;
use App\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if(Auth::user()->validar_permiso('con_perfiles')){
            $roles = Rol::all();
            $controlador = "configuracion";
            $subcon = 'roles';
            $title = "Lista de Perfiles";
            return view('roles.index', compact('title','roles','controlador','subcon'));
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'roles';
        $modulos = Modulo::all();
        $permisos = Permiso::all();
        $roles_permisos = array();

        if(Auth::user()->validar_permiso('con_perfiles')){

            $accion = url('roles/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Perfil";
            $boton = "Crear Perfil";
            return view('roles.create',compact('roles_permisos','modulos','permisos','accion','metodo','titulo','boton','controlador','subcon'));
            
        }else{
            return view('errors.access_denied');
        }
    }

    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'roles';
        $modulos = Modulo::all();
        $permisos = Permiso::all();

        if(Auth::user()->validar_permiso('con_perfiles')){

            $rol = Rol::findOrFail($id);
            $roles_permisos = explode(",",$rol->permisos);
          
            $accion = url("roles/actualizar/{$rol->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Perfil";
            $boton = "Actualizar";
            return view('roles.create',compact('modulos','permisos','roles_permisos','rol','accion','metodo','titulo','boton','controlador','subcon'));

        }else{
            return view('errors.access_denied');
        }
    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre_rol'=>['required','unique:roles,nombre'],
            'permisos'=>'required',
        ],[
            'nombre_rol.required'=>"El campo nombre es requerido",
            'permisos.required'=>"No ha seleccionado ningun permiso",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $permisos_array = $r->permisos;
        $permisos_cadena = ",";

        if($permisos_array != null){
            $n = count($permisos_array);
            for ($i = 0; $i < $n; $i++ ) {
                $permisos_cadena = $permisos_cadena.$permisos_array[$i].",";
            }
        }

        $rol = new Rol;
        $rol->nombre = $r->nombre_rol;
        $rol->permisos = $permisos_cadena;
        $rol->agencias = $r->agencias;
        $rol->date_new = $dateonly;
        $rol->user_new = Auth::id();
        $rol->user_update = Auth::id();
        $rol->created_at = $datehour;
        $rol->updated_at = $datehour;
        $rol->save();

        

        return redirect()->route('roles.index')->with('mensaje', 'Registro ingresado con éxito!');
    }    
    public function update(Request $r, $id)
    {
        $rol = Rol::findOrfail($id);

        $this->validate($r,[
            'nombre_rol' => Rule::unique('roles', 'nombre')->ignore($rol->id,'id'),
            'permisos'=>'required',
        ],[
            'nombre_rol.required'=>"El campo nombre es requerido",
            'nombre_rol.unique'=>"El campo Nombre ya existe",
            'permisos.required'=>"No ha seleccionado ningun permiso",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $permisos_array = $r->permisos;
        $permisos_cadena = ",";

        if($permisos_array != null){
            $n = count($permisos_array);
            for ($i = 0; $i < $n; $i++ ) {
                $permisos_cadena = $permisos_cadena.$permisos_array[$i].",";
            }
        }
        $rol->nombre = $r->nombre_rol;
        $rol->permisos = $permisos_cadena;
        $rol->agencias = $r->agencias;
        $rol->user_update = Auth::id();
        $rol->updated_at = $datehour;
        $rol->save();

        
        return redirect("roles/{$rol->id}/editar")->with('mensaje', 'Actualización realizada con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_perfiles')){

            $rol = Rol::findOrFail($id);
            $titulo = 'Detalle de rol';

            return view('roles.show',compact('titulo','rol'));

        }else{
            return view('errors.access_denied');
        }
    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_perfiles')){
            $rol = Rol::findOrFail($id);
            try {
                $rol->delete($id);
                return redirect()->route('roles.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('roles.index')->with('alerta', 'No se pudo eliminar el registro!');
            } 
        }else{
            return view('errors.access_denied');
        }
    }
}

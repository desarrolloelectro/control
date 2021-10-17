<?php

namespace App\Http\Controllers;

use App\Revisoria_estado;
use App\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Revisoria_estadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'revisoria_estados';

        if(Auth::user()->validar_permiso('con_estados_c')){
            $revisoria_estados = Revisoria_estado::all();
            $title = "Lista de Estados Revisorias";
            return view('revisoria_estados.index', compact('title','revisoria_estados','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'revisoria_estados';
        $roles = Rol::orderBy('nombre','asc')->get();
        $perfiles_bloqueados = array();

        if(Auth::user()->validar_permiso('con_estados_c')){
            $accion = url('revisoria_estados/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Estado Revisorias";
            $boton = "Crear";
            return view('revisoria_estados.create',compact('perfiles_bloqueados','roles','accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'revisoria_estados';

        if(Auth::user()->validar_permiso('con_estados_c')){
            $revisoria_estado = Revisoria_estado::where('id',$id)->firstOrFail();
            $accion = url("revisoria_estados/actualizar/{$revisoria_estado->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Estado Revisorias";
            $boton = "Actualizar";

            $roles = Rol::orderBy('nombre','asc')->get();
            $perfiles_bloqueados = explode(",",$revisoria_estado->perfiles_bloqueados);

            return view('revisoria_estados.create',compact('roles','perfiles_bloqueados','revisoria_estado','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:revisoria_estados,nombre'],
            'color'=>"required",
            'bloquear_perfiles'=>"required",
        ],[
            'nombre.required'=>"El campo Estado es requerido",
            'color.required'=>"El campo Color es requerido",
            'bloquear_perfiles.required'=>"El campo Bloquear Perfiles es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $revisoria_estado = new Revisoria_estado;
        $revisoria_estado->nombre = $r->nombre;
        $revisoria_estado->color = $r->color;
        $revisoria_estado->bloquear_perfiles = $r->bloquear_perfiles;

        $perfiles_bloqueados = $r->perfiles_bloqueados;
        $perfiles_cadena = ",";

        if($perfiles_bloqueados != null){
            $n = count($perfiles_bloqueados);
            for ($i = 0; $i < $n; $i++ ) {
                $perfiles_cadena = $perfiles_cadena.$perfiles_bloqueados[$i].",";
            }
        }

        $revisoria_estado->perfiles_bloqueados = $perfiles_cadena;
        $revisoria_estado->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('revisoria_estados.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('revisoria_estados.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $revisoria_estado = Revisoria_estado::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('revisoria_estados', 'nombre')->ignore($revisoria_estado->id,'id'),
            'color' =>'required'
        ],[
            'nombre.required'=>"El campo Estado Revisoria es requerido",
            'color.required'=>"El campo Color es requerido",
            'nombre.unique'=>"El campo Estado Revisoria ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $revisoria_estado->nombre = $r->nombre;
        $revisoria_estado->color = $r->color;
        $revisoria_estado->bloquear_perfiles = $r->bloquear_perfiles;

        $perfiles_bloqueados = $r->perfiles_bloqueados;
        $perfiles_cadena = ",";

        if($perfiles_bloqueados != null){
            $n = count($perfiles_bloqueados);
            for ($i = 0; $i < $n; $i++ ) {
                $perfiles_cadena = $perfiles_cadena.$perfiles_bloqueados[$i].",";
            }
        }

        $revisoria_estado->perfiles_bloqueados = $perfiles_cadena;
        
        $revisoria_estado->save();

        return redirect()->route('revisoria_estados.index')->with('mensaje', 'Registro actualizado con éxito!');
     
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_estados_c')){
            $revisoria_estado = Revisoria_estado::where('id',$id)->firstOrFail();
            $titulo = 'Detalle de revisoria_estado';
            return view('revisoria_estados.show',compact('titulo','revisoria_estado'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {

        if(Auth::user()->validar_permiso('con_estados_c')){
            $revisoria_estado = Revisoria_estado::where('id',$id)->firstOrFail();
            try {
                $revisoria_estado->delete($id);
                return redirect()->route('revisoria_estados.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('revisoria_estados.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_revisoria_estados(Request $r){
        $revisoria_estados = Revisoria_estado::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('revisoria_estados'))->render();
    	return response()->json(['options'=>$opciones]);
    }
}

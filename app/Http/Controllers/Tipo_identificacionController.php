<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Tipo_identificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Tipo_identificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    { 
        $controlador = "configuracion";
        $subcon = 'tipo_identificaciones';

        if(Auth::user()->validar_permiso('con_tipo_id')){
            $tipo_identificaciones = Tipo_identificacion::where('id','!=','0')->orderBy('nombre','asc')->get();
            $title = "Lista Tipo_identificaciones";
            return view('tipo_identificaciones.index', compact('title','tipo_identificaciones','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'tipo_identificaciones';

        if(Auth::user()->validar_permiso('con_tipo_id')){
            $accion = url('tipo_identificaciones/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Tipo_identificacion";
            $boton = "Crear";
            return view('tipo_identificaciones.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'tipo_identificaciones';

        if(Auth::user()->validar_permiso('con_tipo_id')){
            $tipo_identificacion = Tipo_identificacion::where('id',$id)->firstOrFail();

            $accion = url("tipo_identificaciones/actualizar/{$tipo_identificacion->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Tipo_identificacion";
            $boton = "Actualizar";
            return view('tipo_identificaciones.create',compact('tipo_identificacion','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:tipo_identificaciones,nombre'],
            'abreviatura'=>'required'
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
            'abreviatura.required'=>"El campo Abreviatura es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $tipo_identificacion = new Tipo_identificacion;
        $tipo_identificacion->nombre = $r->nombre;
        $tipo_identificacion->abreviatura = $r->abreviatura;
        $tipo_identificacion->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('tipo_identificaciones.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('tipo_identificaciones.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $tipo_identificacion = Tipo_identificacion::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('tipo_identificaciones', 'nombre')->ignore($tipo_identificacion->id,'id'),
            'abreviatura' => 'required'
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
            'nombre.unique'=>"El campo Nombre ya se encuentra registrado",
            'abreviatura.required'=>"El campo Abreviatura es requerido",

        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $tipo_identificacion->nombre = $r->nombre;
        $tipo_identificacion->abreviatura = $r->abreviatura;
        
        $tipo_identificacion->save();

        return redirect()->route('tipo_identificaciones.index')->with('mensaje', 'Registro actualizado con éxito!');

    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_tipo_id')){
            $tipo_identificacion = Tipo_identificacion::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Tipo_identificacion';
            return view('tipo_identificaciones.show',compact('titulo','tipo_identificacion'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_tipo_id')){
            $tipo_identificacion = Tipo_identificacion::where('id',$id)->firstOrFail();
            try {
                $tipo_identificacion->delete($id);
                return redirect()->route('tipo_identificaciones.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('tipo_identificaciones.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_tipo_identificaciones(Request $r){
        $tipo_identificaciones = Tipo_identificacion::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_identificaciones'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $tipo_identificacion = new Tipo_identificacion;
        $tipo_identificacion->nombre = $r->nombre;
        $tipo_identificacion->save();

        $tipo_identificaciones = Tipo_identificacion::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_identificaciones'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

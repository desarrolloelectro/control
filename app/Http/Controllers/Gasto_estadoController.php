<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Gasto_estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Gasto_estadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'gasto_estados';

        if(Auth::user()->validar_permiso('con_estados_g')){
            $gasto_estados = Gasto_estado::where('id','!=','0')->orderBy('id','asc')->get();
            $title = "Lista Gasto_estados";
            return view('gasto_estados.index', compact('title','gasto_estados','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'gasto_estados';

        if(Auth::user()->validar_permiso('con_estados_g')){
            $accion = url('gasto_estados/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Gasto_estado";
            $boton = "Crear";
            return view('gasto_estados.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'gasto_estados';

        if(Auth::user()->validar_permiso('con_estados_g')){
            $gasto_estado = Gasto_estado::where('id',$id)->firstOrFail();

            $accion = url("gasto_estados/actualizar/{$gasto_estado->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Gasto_estado";
            $boton = "Actualizar";
            return view('gasto_estados.create',compact('gasto_estado','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:gasto_estados,nombre'],
            'color'=>'required'
        ],[
            'nombre.required'=>"El campo Estado es requerido",
            'color.required'=>"El campo Color es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $gasto_estado = new Gasto_estado;
        $gasto_estado->nombre = $r->nombre;
        $gasto_estado->color = $r->color;
        $gasto_estado->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('gasto_estados.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('gasto_estados.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $gasto_estado = Gasto_estado::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('gasto_estados', 'nombre')->ignore($gasto_estado->id,'id'),
            'color'=>'required'
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
            'color.unique'=>"El campo Color es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $gasto_estado->nombre = $r->nombre;
        $gasto_estado->color = $r->color;
        
        $gasto_estado->save();

        return redirect()->route('gasto_estados.index')->with('mensaje', 'Registro actualizado con éxito!');

    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_estados_g')){
            $gasto_estado = Gasto_estado::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Gasto_estado';
            return view('gasto_estados.show',compact('titulo','gasto_estado'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {

        if(Auth::user()->validar_permiso('con_estados_g')){
            $gasto_estado = Gasto_estado::where('id',$id)->firstOrFail();
            try {
                $gasto_estado->delete($id);
                return redirect()->route('gasto_estados.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('gasto_estados.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_gasto_estados(Request $r){
        $gasto_estados = Gasto_estado::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('gasto_estados'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $gasto_estado = new Gasto_estado;
        $gasto_estado->nombre = $r->nombre;
        $gasto_estado->save();

        $gasto_estados = Gasto_estado::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('gasto_estados'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

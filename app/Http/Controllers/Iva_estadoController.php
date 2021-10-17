<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Iva_estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Iva_estadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'iva_estados';

        if(Auth::user()->validar_permiso('con_estados_g')){
            $iva_estados = Iva_estado::where('id','!=','0')->orderBy('id','asc')->get();
            $title = "Lista Iva_estados";
            return view('iva_estados.index', compact('title','iva_estados','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'iva_estados';

        if(Auth::user()->validar_permiso('con_estados_g')){
            $accion = url('iva_estados/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Iva_estado";
            $boton = "Crear";
            return view('iva_estados.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'iva_estados';

        if(Auth::user()->validar_permiso('con_estados_g')){
            $iva_estado = Iva_estado::where('id',$id)->firstOrFail();

            $accion = url("iva_estados/actualizar/{$iva_estado->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Iva_estado";
            $boton = "Actualizar";
            return view('iva_estados.create',compact('iva_estado','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>'required',
            'color'=>'required',
            'tipo'=>'required'
        ],[
            'nombre.required'=>"El campo Estado es requerido",
            'color.required'=>"El campo Color es requerido",
            'tipo.required'=>"El campo Tipo es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $iva_estado = new Iva_estado;
        $iva_estado->nombre = $r->nombre;
        $iva_estado->color = $r->color;
        $iva_estado->tipo = $r->tipo;
        $iva_estado->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('iva_estados.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('iva_estados.index')->with('mensaje', 'Registro ingresado con éxito!');
        }
    }    
    public function update(Request $r, $id)
    {
        $iva_estado = Iva_estado::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => 'required',
            'color'=>'required',
            'tipo'=>'required'
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
            'color.required'=>"El campo Color es requerido",
            'tipo.required'=>"El campo Tipo es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $iva_estado->nombre = $r->nombre;
        $iva_estado->color = $r->color;
        $iva_estado->tipo = $r->tipo;
        
        $iva_estado->save();

        return redirect()->route('iva_estados.index')->with('mensaje', 'Registro actualizado con éxito!');

    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_estados_g')){
            $iva_estado = Iva_estado::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Iva_estado';
            return view('iva_estados.show',compact('titulo','iva_estado'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {

        if(Auth::user()->validar_permiso('con_estados_g')){
            $iva_estado = Iva_estado::where('id',$id)->firstOrFail();
            try {
                $iva_estado->delete($id);
                return redirect()->route('iva_estados.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('iva_estados.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_iva_estados(Request $r){
        $iva_estados = Iva_estado::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('iva_estados'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $iva_estado = new Iva_estado;
        $iva_estado->nombre = $r->nombre;
        $iva_estado->save();

        $iva_estados = Iva_estado::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('iva_estados'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

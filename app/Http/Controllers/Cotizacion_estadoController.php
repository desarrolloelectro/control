<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Cotizacion_estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Cotizacion_estadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'cotizacion_estados';

        if(Auth::user()->validar_permiso('con_estados_c')){
            $cotizacion_estados = Cotizacion_estado::where('id','!=','0')->orderBy('id','asc')->get();
            $title = "Lista Cotizacion_estados";
            return view('cotizacion_estados.index', compact('title','cotizacion_estados','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'cotizacion_estados';

        if(Auth::user()->validar_permiso('con_estados_c')){
            $accion = url('cotizacion_estados/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Cotizacion_estado";
            $boton = "Crear";
            return view('cotizacion_estados.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'cotizacion_estados';

        if(Auth::user()->validar_permiso('con_estados_c')){
            $cotizacion_estado = Cotizacion_estado::where('id',$id)->firstOrFail();
            /**if($cotizacion_estado == null){
                return response()->view('errors.404',[],404);
            }*/
            $accion = url("cotizacion_estados/actualizar/{$cotizacion_estado->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Cotizacion_estado";
            $boton = "Actualizar";
            return view('cotizacion_estados.create',compact('cotizacion_estado','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:cotizacion_estados,nombre'],
            'color'=>'required'
        ],[
            'nombre.required'=>"El campo Estado es requerido",
            'color.required'=>"El campo Color es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $cotizacion_estado = new Cotizacion_estado;
        $cotizacion_estado->nombre = $r->nombre;
        $cotizacion_estado->color = $r->color;
        $cotizacion_estado->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('cotizacion_estados.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('cotizacion_estados.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $cotizacion_estado = Cotizacion_estado::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('cotizacion_estados', 'nombre')->ignore($cotizacion_estado->id,'id'),
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
     
        $cotizacion_estado->nombre = $r->nombre;
        $cotizacion_estado->color = $r->color;
        
        $cotizacion_estado->save();

        return redirect()->route('cotizacion_estados.index')->with('mensaje', 'Registro actualizado con éxito!');

    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_estados_c')){
            $cotizacion_estado = Cotizacion_estado::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Cotizacion_estado';
            return view('cotizacion_estados.show',compact('titulo','cotizacion_estado'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail(Auth::id());     
        $usuario = $usuario->nombre;

        if(Auth::user()->validar_permiso('con_estados_c')){
            $cotizacion_estado = Cotizacion_estado::where('id',$id)->firstOrFail();
            try {
                $cotizacion_estado->delete($id);
                return redirect()->route('cotizacion_estados.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('cotizacion_estados.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_cotizacion_estados(Request $r){
        $cotizacion_estados = Cotizacion_estado::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('cotizacion_estados'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $cotizacion_estado = new Cotizacion_estado;
        $cotizacion_estado->nombre = $r->nombre;
        $cotizacion_estado->save();

        $cotizacion_estados = Cotizacion_estado::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('cotizacion_estados'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

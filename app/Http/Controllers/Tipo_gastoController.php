<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Tipo_gasto;
use App\Agencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Tipo_gastoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'tipo_gastos';

        if(Auth::user()->validar_permiso('con_tipo_gastos')){
            $tipo_gastos = Tipo_gasto::where('id','!=','0')->orderBy('id','asc')->get();
            $title = "Lista Tipo_gastos";
            return view('tipo_gastos.index', compact('title','tipo_gastos','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {        
        $controlador = "configuracion";
        $subcon = 'tipo_gastos';

        $regionales = Agencia::select('agenreg')->where('agenreg',"!=","")->distinct()->get();
        $agencias = Agencia::orderBy('agennom','asc')->get();
        $lista_agencias = array();


        if(Auth::user()->validar_permiso('con_tipo_gastos')){
            $accion = url('tipo_gastos/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Tipo_gasto";
            $boton = "Crear";
            return view('tipo_gastos.create',compact('regionales','agencias','lista_agencias','accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'tipo_gastos';

        $regionales = Agencia::select('agenreg')->where('agenreg',"!=","")->distinct()->get();
        $agencias = Agencia::orderBy('agennom','asc')->get();

        if(Auth::user()->validar_permiso('con_tipo_gastos')){
            $tipo_gasto = Tipo_gasto::where('id',$id)->firstOrFail();
            $lista_agencias = explode(",",$tipo_gasto->agencias);

            $accion = url("tipo_gastos/actualizar/{$tipo_gasto->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Tipo_gasto";
            $boton = "Actualizar";
            return view('tipo_gastos.create',compact('agencias','regionales','lista_agencias','tipo_gasto','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'tipo'=>['required','unique:tipo_gastos,tipo'],
            'nombre'=>['required','unique:tipo_gastos,nombre'],
            'agencias'=>'required'
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
            'tipo.required'=>"El campo Tipo Gasto es requerido",
            'agencias.required'=>"El campo Descripción es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $tipo_gasto = new Tipo_gasto;
        $tipo_gasto->tipo = $r->tipo;
        $tipo_gasto->nombre = $r->nombre;
        
        $agencias_array = $r->agencias;
        $agencias_cadena = ",";

        if($agencias_array != null){
            $n = count($agencias_array);
            for ($i = 0; $i < $n; $i++ ) {
                $agencias_cadena = $agencias_cadena.$agencias_array[$i].",";
            }
        }

        $tipo_gasto->agencias = $agencias_cadena;
        $tipo_gasto->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('tipo_gastos.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('tipo_gastos.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $tipo_gasto = Tipo_gasto::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'tipo' => Rule::unique('tipo_gastos', 'tipo')->ignore($tipo_gasto->id,'id'),
            'nombre' => Rule::unique('tipo_gastos', 'nombre')->ignore($tipo_gasto->id,'id'),
            'agencias' => 'required'
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
            'tipo.required'=>"El campo Tipo Gasto es requerido",
            'nombre.unique'=>"El campo Nombre ya se encuentra registrado",
            'agencias.required'=>"El campo Agencias es requerido",

        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $tipo_gasto->tipo = $r->tipo;
        $tipo_gasto->nombre = $r->nombre;

        $agencias_array = $r->agencias;
        $agencias_cadena = ",";

        if($agencias_array != null){
            $n = count($agencias_array);
            for ($i = 0; $i < $n; $i++ ) {
                $agencias_cadena = $agencias_cadena.$agencias_array[$i].",";
            }
        }

        $tipo_gasto->agencias = $agencias_cadena;
        
        $tipo_gasto->save();

        return redirect("tipo_gastos/{$tipo_gasto->id}/editar")->with('mensaje', 'Actualización realizada con éxito!');


    }


    public function show($id)
    {
        if(Auth::user()->validar_permiso('con_tipo_gastos')){
            $tipo_gasto = Tipo_gasto::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Tipo_gasto';
            return view('tipo_gastos.show',compact('titulo','tipo_gasto'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_tipo_gastos')){
            $tipo_gasto = Tipo_gasto::where('id',$id)->firstOrFail();
            try {
                $tipo_gasto->delete($id);
                return redirect()->route('tipo_gastos.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('tipo_gastos.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_tipo_gastos(Request $r){
        $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_gastos'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $tipo_gasto = new Tipo_gasto;
        $tipo_gasto->nombre = $r->nombre;
        $tipo_gasto->save();

        $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_gastos'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

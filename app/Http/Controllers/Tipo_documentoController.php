<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Tipo_documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Tipo_documentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'tipo_documentos';

        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_documentos = Tipo_documento::where('id','!=','0')->orderBy('id','asc')->get();
            $title = "Lista Tipo_documentos";
            return view('tipo_documentos.index', compact('title','tipo_documentos','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'tipo_documentos';

        if(Auth::user()->validar_permiso('con_areas')){
            $accion = url('tipo_documentos/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Tipo_documento";
            $boton = "Crear";
            return view('tipo_documentos.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'tipo_documentos';

        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_documento = Tipo_documento::where('id',$id)->firstOrFail();

            $accion = url("tipo_documentos/actualizar/{$tipo_documento->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Tipo_documento";
            $boton = "Actualizar";
            return view('tipo_documentos.create',compact('tipo_documento','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>'required',
            'codigo'=>'required',
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $tipo_documento = new Tipo_documento;
        $tipo_documento->codigo = $r->codigo;
        $tipo_documento->nombre = $r->nombre;
        $tipo_documento->codciu = $r->codciu;
        $tipo_documento->ciudad = $r->ciudad;
        $tipo_documento->coddpto = $r->coddpto;
        $tipo_documento->depto = $r->depto;
        $tipo_documento->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('tipo_documentos.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('tipo_documentos.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $tipo_documento = Tipo_documento::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'codigo' => 'required',
            'nombre' => 'required',
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $tipo_documento->codigo = $r->codigo;
        $tipo_documento->nombre = $r->nombre;
        $tipo_documento->codciu = $r->codciu;
        $tipo_documento->ciudad = $r->ciudad;
        $tipo_documento->coddpto = $r->coddpto;
        $tipo_documento->depto = $r->depto;
        
        $tipo_documento->save();

        return redirect()->route('tipo_documentos.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_documento = Tipo_documento::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Tipo_documento';
            return view('tipo_documentos.show',compact('titulo','tipo_documento'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_documento = Tipo_documento::where('id',$id)->firstOrFail();
            try {
                $tipo_documento->delete($id);
                return redirect()->route('tipo_documentos.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('tipo_documentos.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_tipo_documentos(Request $r){
        $tipo_documentos = Tipo_documento::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_documentos'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $tipo_documento = new Tipo_documento;
        $tipo_documento->nombre = $r->nombre;
        $tipo_documento->save();

        $tipo_documentos = Tipo_documento::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_documentos'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

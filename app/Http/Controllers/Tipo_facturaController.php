<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Tipo_factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Tipo_facturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'tipo_facturas';

        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_facturas = Tipo_factura::where('id','!=','0')->orderBy('nombre','asc')->get();
            $title = "Lista Tipo_facturas";
            return view('tipo_facturas.index', compact('title','tipo_facturas','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'tipo_facturas';

        if(Auth::user()->validar_permiso('con_areas')){
            $accion = url('tipo_facturas/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Tipo_factura";
            $boton = "Crear";
            return view('tipo_facturas.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'tipo_facturas';

        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_factura = Tipo_factura::where('id',$id)->firstOrFail();

            $accion = url("tipo_facturas/actualizar/{$tipo_factura->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Tipo_factura";
            $boton = "Actualizar";
            return view('tipo_facturas.create',compact('tipo_factura','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:tipo_facturas,nombre'],
        ],[
            'nombre.required'=>"El campo Estado es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $tipo_factura = new Tipo_factura;
        $tipo_factura->codigo = $r->codigo;
        $tipo_factura->nombre = $r->nombre;
        $tipo_factura->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('tipo_facturas.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('tipo_facturas.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $tipo_factura = Tipo_factura::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('tipo_facturas', 'nombre')->ignore($tipo_factura->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $tipo_factura->codigo = $r->codigo;
        $tipo_factura->nombre = $r->nombre;
        
        $tipo_factura->save();

        return redirect()->route('tipo_facturas.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_factura = Tipo_factura::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Tipo_factura';
            return view('tipo_facturas.show',compact('titulo','tipo_factura'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_factura = Tipo_factura::where('id',$id)->firstOrFail();
            try {
                $tipo_factura->delete($id);
                return redirect()->route('tipo_facturas.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('tipo_facturas.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_tipo_facturas(Request $r){
        $tipo_facturas = Tipo_factura::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_facturas'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $tipo_factura = new Tipo_factura;
        $tipo_factura->nombre = $r->nombre;
        $tipo_factura->save();

        $tipo_facturas = Tipo_factura::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_facturas'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

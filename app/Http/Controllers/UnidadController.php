<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class UnidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'unidades';

        if(Auth::user()->validar_permiso('con_areas')){
            $unidades = Unidad::where('id','!=','0')->orderBy('nombre','asc')->get();
            $title = "Lista Unidades";
            return view('unidades.index', compact('title','unidades','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'unidades';

        if(Auth::user()->validar_permiso('con_areas')){
            $accion = url('unidades/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Unidad";
            $boton = "Crear";
            return view('unidades.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'unidades';

        if(Auth::user()->validar_permiso('con_areas')){
            $unidad = Unidad::where('id',$id)->firstOrFail();

            $accion = url("unidades/actualizar/{$unidad->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Unidad";
            $boton = "Actualizar";
            return view('unidades.create',compact('unidad','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:unidades,nombre'],
        ],[
            'nombre.required'=>"El campo Estado es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $unidad = new Unidad;
        $unidad->codigo = $r->codigo;
        $unidad->nombre = $r->nombre;
        $unidad->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('unidades.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('unidades.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $unidad = Unidad::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('unidades', 'nombre')->ignore($unidad->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $unidad->codigo = $r->codigo;
        $unidad->nombre = $r->nombre;
        
        $unidad->save();

        return redirect()->route('unidades.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_areas')){
            $unidad = Unidad::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Unidad';
            return view('unidades.show',compact('titulo','unidad'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_areas')){
            $unidad = Unidad::where('id',$id)->firstOrFail();
            try {
                $unidad->delete($id);
                return redirect()->route('unidades.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('unidades.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_unidades(Request $r){
        $unidades = Unidad::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('unidades'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $unidad = new Unidad;
        $unidad->nombre = $r->nombre;
        $unidad->save();

        $unidades = Unidad::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('unidades'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

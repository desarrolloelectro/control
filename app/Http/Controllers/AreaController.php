<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'areas';

        if(Auth::user()->validar_permiso('con_areas')){
            $areas = Area::where('id','!=','0')->orderBy('nombre','asc')->get();
            $title = "Lista Áreas";
            return view('areas.index', compact('title','areas','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'areas';

        if(Auth::user()->validar_permiso('con_areas')){
            $accion = url('areas/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Área";
            $boton = "Crear";
            return view('areas.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'areas';

        if(Auth::user()->validar_permiso('con_areas')){
            $area = Area::where('id',$id)->firstOrFail();

            $accion = url("areas/actualizar/{$area->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Área";
            $boton = "Actualizar";
            return view('areas.create',compact('area','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:areas,nombre'],
        ],[
            'nombre.required'=>"El campo Estado es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $area = new Area;
        $area->nombre = $r->nombre;
        $area->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('areas.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('areas.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $area = Area::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('areas', 'nombre')->ignore($area->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $area->nombre = $r->nombre;
        
        $area->save();

        return redirect()->route('areas.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_areas')){
            $area = Area::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Area';
            return view('areas.show',compact('titulo','area'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_areas')){
            $area = Area::where('id',$id)->firstOrFail();
            try {
                $area->delete($id);
                return redirect()->route('areas.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('areas.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_areas(Request $r){
        $areas = Area::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('areas'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $area = new Area;
        $area->nombre = $r->nombre;
        $area->save();

        $areas = Area::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('areas'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

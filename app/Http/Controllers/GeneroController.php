<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Genero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class GeneroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'generos';

        if(Auth::user()->validar_permiso('con_areas')){
            $generos = Genero::where('id','!=','0')->orderBy('codigo','asc')->get();
            $title = "Lista Generos";
            return view('generos.index', compact('title','generos','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'generos';

        if(Auth::user()->validar_permiso('con_areas')){
            $accion = url('generos/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Genero";
            $boton = "Crear";
            return view('generos.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'generos';

        if(Auth::user()->validar_permiso('con_areas')){
            $genero = Genero::where('id',$id)->firstOrFail();

            $accion = url("generos/actualizar/{$genero->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Genero";
            $boton = "Actualizar";
            return view('generos.create',compact('genero','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:generos,nombre'],
        ],[
            'nombre.required'=>"El campo Estado es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $genero = new Genero;
        $genero->codigo = $r->codigo;
        $genero->nombre = $r->nombre;
        $genero->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('generos.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('generos.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $genero = Genero::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('generos', 'nombre')->ignore($genero->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $genero->codigo = $r->codigo;
        $genero->nombre = $r->nombre;
        
        $genero->save();

        return redirect()->route('generos.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_areas')){
            $genero = Genero::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Genero';
            return view('generos.show',compact('titulo','genero'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_areas')){
            $genero = Genero::where('id',$id)->firstOrFail();
            try {
                $genero->delete($id);
                return redirect()->route('generos.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('generos.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_generos(Request $r){
        $generos = Genero::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('generos'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $genero = new Genero;
        $genero->nombre = $r->nombre;
        $genero->save();

        $generos = Genero::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('generos'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'categorias';

        if(Auth::user()->validar_permiso('con_areas')){
            $categorias = Categoria::where('id','!=','0')->orderBy('nombre','asc')->get();
            $title = "Lista Categorias";
            return view('categorias.index', compact('title','categorias','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'categorias';

        if(Auth::user()->validar_permiso('con_areas')){
            $accion = url('categorias/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Categoria";
            $boton = "Crear";
            return view('categorias.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'categorias';

        if(Auth::user()->validar_permiso('con_areas')){
            $categoria = Categoria::where('id',$id)->firstOrFail();

            $accion = url("categorias/actualizar/{$categoria->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Categoria";
            $boton = "Actualizar";
            return view('categorias.create',compact('categoria','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:categorias,nombre'],
        ],[
            'nombre.required'=>"El campo Estado es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $categoria = new Categoria;
        $categoria->codigo = $r->codigo;
        $categoria->nombre = $r->nombre;
        $categoria->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('categorias.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('categorias.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $categoria = Categoria::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('categorias', 'nombre')->ignore($categoria->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $categoria->codigo = $r->codigo;
        $categoria->nombre = $r->nombre;
        
        $categoria->save();

        return redirect()->route('categorias.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_areas')){
            $categoria = Categoria::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Categoria';
            return view('categorias.show',compact('titulo','categoria'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_areas')){
            $categoria = Categoria::where('id',$id)->firstOrFail();
            try {
                $categoria->delete($id);
                return redirect()->route('categorias.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('categorias.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_categorias(Request $r){
        $categorias = Categoria::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('categorias'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $categoria = new Categoria;
        $categoria->nombre = $r->nombre;
        $categoria->save();

        $categorias = Categoria::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('categorias'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

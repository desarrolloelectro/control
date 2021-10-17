<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'empresas';

        if(Auth::user()->validar_permiso('con_empresas')){
            $empresas = Empresa::where('id','!=','0')->orderBy('nombre','asc')->get();
            $title = "Lista Empresas";
            return view('empresas.index', compact('title','empresas','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'empresas';

        if(Auth::user()->validar_permiso('con_empresas')){
            $accion = url('empresas/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Empresa";
            $boton = "Crear";
            return view('empresas.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'empresas';

        if(Auth::user()->validar_permiso('con_empresas')){
            $empresa = Empresa::where('id',$id)->firstOrFail();

            $accion = url("empresas/actualizar/{$empresa->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Empresa";
            $boton = "Actualizar";
            return view('empresas.create',compact('empresa','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:empresas,nombre'],
        ],[
            'nombre.required'=>"El campo Estado es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $empresa = new Empresa;
        $empresa->nombre = $r->nombre;
        $empresa->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('empresas.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('empresas.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $empresa = Empresa::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('empresas', 'nombre')->ignore($empresa->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $empresa->nombre = $r->nombre;
        
        $empresa->save();

        return redirect()->route('empresas.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_empresas')){
            $empresa = Empresa::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Empresa';
            return view('empresas.show',compact('titulo','empresa'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_empresas')){
            $empresa = Empresa::where('id',$id)->firstOrFail();
            try {
                $empresa->delete($id);
                return redirect()->route('empresas.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('empresas.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_empresas(Request $r){
        $empresas = Empresa::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('empresas'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $empresa = new Empresa;
        $empresa->nombre = $r->nombre;
        $empresa->save();

        $empresas = Empresa::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('empresas'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

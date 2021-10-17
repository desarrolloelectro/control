<?php

namespace App\Http\Controllers;
use App\Usuario_agencia;
use App\Agencia;
use App\Tipo_gasto;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Usuario_agenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if(Auth::user()->validar_permiso('con_usu_agen')){
            $usuario_agencias = Usuario_agencia::all();
            $controlador = "configuracion";
            $title = "Lista Usuarios Agencias";
            return view('usuario_agencias.index', compact('title','usuario_agencias','controlador'));
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $regionales = Agencia::select('agenreg')->where('agenreg',"!=","")->distinct()->get();
        $agencias = Agencia::where('activo','1')->orderBy('agennom','asc')->get();
        $usuarios = Usuario::where('nivel_control','!=',0)->orderBy('nombre','asc')->get();
        $lista_agencias = array();

        if(Auth::user()->validar_permiso('con_usu_agen')){

            $accion = url('usuario_agencias/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear";
            $boton = "Crear";
            return view('usuario_agencias.create',compact('usuarios','lista_agencias','regionales','agencias','accion','metodo','titulo','boton','controlador'));
            
        }else{
            return view('errors.access_denied');
        }
    }

    public function edit($id)
    {
        $controlador = "configuracion";
        $regionales = Agencia::select('agenreg')->where('agenreg',"!=","")->distinct()->get();
        $agencias = Agencia::orderBy('agennom','asc')->get();
        $usuarios = Usuario::where('nivel_control','!=',0)->orderBy('nombre','asc')->get();

        if(Auth::user()->validar_permiso('con_usu_agen')){

            $usuario_agencia = Usuario_agencia::findOrFail($id);
            $lista_agencias = explode(",",$usuario_agencia->agencias);
          
            $accion = url("usuario_agencias/actualizar/{$usuario_agencia->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar";
            $boton = "Actualizar";
            return view('usuario_agencias.create',compact('regionales','agencias','usuarios','lista_agencias','usuario_agencia','accion','metodo','titulo','boton','controlador'));

        }else{
            return view('errors.access_denied');
        }
    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'usuario_id'=>['required','unique:usuario_agencias,usuario_id'],
            'agencias'=>'required',
        ],[
            'usuario_id.required'=>"El campo Usuario es requerido",
            'agencias.required'=>"No ha seleccionado ninguna agencia",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $agencias_array = $r->agencias;
        $agencias_cadena = ",";

        if($agencias_array != null){
            $n = count($agencias_array);
            for ($i = 0; $i < $n; $i++ ) {
                $agencias_cadena = $agencias_cadena.$agencias_array[$i].",";
            }
        }

        $usuario_agencia = new Usuario_agencia;
        $usuario_agencia->usuario_id = $r->usuario_id;
        $usuario_agencia->agencias = $agencias_cadena;
        $usuario_agencia->date_new = $dateonly;
        $usuario_agencia->user_new = Auth::id();
        $usuario_agencia->user_update = Auth::id();
        $usuario_agencia->created_at = $datehour;
        $usuario_agencia->updated_at = $datehour;
        $usuario_agencia->save();

        return redirect()->route('usuario_agencias.index')->with('mensaje', 'Registro ingresado con éxito!');
    }    
    public function update(Request $r, $id)
    {
        $rol = Usuario_agencia::findOrfail($id);

        $this->validate($r,[
            'agencias'=>'required',
        ],[
            'agencias.required'=>"No ha seleccionado ninguna Agencia",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $agencias_array = $r->agencias;
        $agencias_cadena = ",";

        if($agencias_array != null){
            $n = count($agencias_array);
            for ($i = 0; $i < $n; $i++ ) {
                $agencias_cadena = $agencias_cadena.$agencias_array[$i].",";
            }
        }
        $rol->agencias = $agencias_cadena;
        $rol->user_update = Auth::id();
        $rol->updated_at = $datehour;
        $rol->save();

        
        return redirect("usuario_agencias/{$rol->id}/editar")->with('mensaje', 'Actualización realizada con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_usu_agen')){

            $rol = Usuario_agencia::findOrFail($id);
            $titulo = 'Detalle de rol';

            return view('usuario_agencias.show',compact('titulo','rol'));

        }else{
            return view('errors.access_denied');
        }
    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_usu_agen')){
            $rol = Usuario_agencia::findOrFail($id);
            try {
                $rol->delete($id);
                return redirect()->route('usuario_agencias.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('usuario_agencias.index')->with('alerta', 'No se pudo eliminar el registro!');
            } 
        }else{
            return view('errors.access_denied');
        }
    }
}

<?php

namespace App\Http\Controllers;

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

class AgenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'agencias';

        if(Auth::user()->validar_permiso('con_agencias')){
            $agencias = Agencia::orderBy('codagen','asc')->get();
            $title = "Lista de agencias";
            return view('agencias.index', compact('title','agencias','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'agencias';

        if(Auth::user()->validar_permiso('con_agencias')){
            $accion = url('agencias/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear agencia";
            $boton = "Crear agencia";
            return view('agencias.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'agencias';

        if(Auth::user()->validar_permiso('con_agencias')){
            $agencia = Agencia::where('codagen',$id)->firstOrFail();
 
            $accion = url("agencias/actualizar/{$agencia->codagen}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar agencia";
            $boton = "Actualizar";
            return view('agencias.create',compact('agencia','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'codagen'=>['required','unique:toolset_perf.agencia,codagen'],
            'agennom'=>['required','unique:toolset_perf.agencia,agennom'],
            'agensucur'=>'required',
            'agenreg'=>'required',
            'agenpertenece'=>'required|numeric|max:999999',
            'activo'=>'required',
        ],[
            'codagen.required'=>"El campo Código Agencia es requerido",
            'agennom.required'=>"El campo Nombre Agencia es requerido",
            'agensucur.required'=>"El campo Sucursal es requerido",
            'agenreg.required'=>"El campo Regional es requerido",
            'agenpertenece.required'=>"El campo Pertenece es requerido",
            'activo.required'=>"El campo Estado es requerido",
            'codagen.unique'=>"El campo Código Agencia ya se encuentra registrado",
            'agennom.unique'=>"El campo Nombre Agencia ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $agencia = new Agencia;
        $agencia->codagen = $r->codagen;
        $agencia->agennom = $r->agennom;
        $agencia->agensucur = $r->agensucur;
        $agencia->agenreg = $r->agenreg;
        $agencia->agenpertenece = $r->agenpertenece;
        $agencia->activo = $r->activo;

        $agencia->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('agencias.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('agencias.index')->with('mensaje', 'Registro ingresado con éxito!');
        }
    }    
    public function update(Request $r, $id)
    {
        $agencia = Agencia::where('codagen',$id)->firstOrFail();

        $this->validate($r,[
            'agennom' => Rule::unique('toolset_perf.agencia', 'agennom')->ignore($agencia->codagen,'codagen'),
            'codagen'=>'required',
            'agensucur'=>'required',
            'agenreg'=>'required',
            'agenpertenece'=>'required',
            'activo'=>'required',
        ],[
            'codagen.required'=>"El campo Código Agencia es requerido",
            'agennom.required'=>"El campo Nombre Agencia es requerido",
            'agensucur.required'=>"El campo Sucursal es requerido",
            'agenreg.required'=>"El campo Regional es requerido",
            'agenpertenece.required'=>"El campo Pertenece es requerido",
            'activo.required'=>"El campo Estado es requerido",
            'codagen.unique'=>"El campo Código Agencia ya se encuentra registrado",
            'agennom.unique'=>"El campo Nombre Agencia ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $agencia->agennom = $r->agennom;
        $agencia->agensucur = $r->agensucur;
        $agencia->agenreg = $r->agenreg;
        $agencia->agenpertenece = $r->agenpertenece;
        $agencia->activo = $r->activo;

        $agencia->save();

        
        return redirect("agencias/{$agencia->codagen}/editar")->with('mensaje', 'Actualización realizada con éxito!');
        //return redirect("/agencias/show/{$agencia->codagen}");
        //return redirect()->route('agencias.show',['agencia'=>$agencia]);
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_agencias')){
            $agencia = Agencia::where('codagen',$id)->firstOrFail();
            $titulo = 'Detalle de agencia';
            return view('agencias.show',compact('titulo','agencia'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {

        if(Auth::user()->validar_permiso('con_agencias')){
            $agencia = Agencia::where('codagen',$id)->firstOrFail();
            try {
                //$agencia->delete($id);
                return redirect()->route('agencias.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('agencias.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }
    public function actualizar_agencias(Request $r){
        $agencias = Agencia::orderBy('agennom','asc')->get();
        $opciones = view('cargar_select',compact('agencias'))->render();
    	return response()->json(['options'=>$opciones]);
    }
}

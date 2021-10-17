<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Envio_correo;
use App\Tipo_pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Envio_correoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'envio_correos';

        if(Auth::user()->validar_permiso('con_bancos')){
            $envio_correos = Envio_correo::where('id','!=','0')->orderBy('nombre','asc')->get();
            $title = "Lista Envio_correos";
            return view('envio_correos.index', compact('title','envio_correos','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'envio_correos';

        if(Auth::user()->validar_permiso('con_bancos')){
            $accion = url('envio_correos/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Envio_correo";
            $boton = "Crear";

            $tipo_pagos = Tipo_pago::where('id','!=','1')->orderBy('nombre','asc')->get();
            return view('envio_correos.create',compact('tipo_pagos','accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'envio_correos';

        if(Auth::user()->validar_permiso('con_bancos')){
            $envio_correo = Envio_correo::where('id',$id)->firstOrFail();

            $accion = url("envio_correos/actualizar/{$envio_correo->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Envio_correo";
            $boton = "Actualizar";

            $tipo_pagos = Tipo_pago::where('id','!=','1')->orderBy('nombre','asc')->get();
            
            return view('envio_correos.create',compact('tipo_pagos','envio_correo','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>'required',
            'correo'=>'required',
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
            'correo.required'=>"El campo Correo es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $envio_correo = new Envio_correo;
        $envio_correo->nombre = $r->nombre;
        $envio_correo->correo = $r->correo;
        $envio_correo->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('envio_correos.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('envio_correos.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $envio_correo = Envio_correo::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre'=>'required',
            'correo'=>'required',
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $envio_correo->nombre = $r->nombre;
        $envio_correo->correo = $r->correo;
        
        $envio_correo->save();

        return redirect()->route('envio_correos.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_bancos')){
            $envio_correo = Envio_correo::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Envio_correo';
            return view('envio_correos.show',compact('titulo','envio_correo'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_bancos')){
            $envio_correo = Envio_correo::where('id',$id)->firstOrFail();
            try {
                $envio_correo->delete($id);
                return redirect()->route('envio_correos.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('envio_correos.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_envio_correos(Request $r){
        $envio_correos = Envio_correo::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('envio_correos'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $envio_correo = new Envio_correo;
        $envio_correo->nombre = $r->nombre;
        $envio_correo->save();

        $envio_correos = Envio_correo::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('envio_correos'))->render();
        return response()->json(['options'=>$opciones]);
        
    }

    public function cargar_envio_correos(Request $r){
        $envio_correos = Envio_correo::where('tipo_pago_id',$r->tipo_pago_id)->orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('envio_correos'))->render();
    	return response()->json(['options'=>$opciones]);
    }
}

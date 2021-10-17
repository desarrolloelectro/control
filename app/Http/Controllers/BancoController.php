<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Banco;
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

class BancoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'bancos';

        if(Auth::user()->validar_permiso('con_bancos')){
            $bancos = Banco::where('id','!=','0')->orderBy('nombre','asc')->get();
            $title = "Lista Bancos";
            return view('bancos.index', compact('title','bancos','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'bancos';

        if(Auth::user()->validar_permiso('con_bancos')){
            $accion = url('bancos/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Banco";
            $boton = "Crear";

            $tipo_pagos = Tipo_pago::where('id','!=','1')->orderBy('nombre','asc')->get();
            return view('bancos.create',compact('tipo_pagos','accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'bancos';

        if(Auth::user()->validar_permiso('con_bancos')){
            $banco = Banco::where('id',$id)->firstOrFail();

            $accion = url("bancos/actualizar/{$banco->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Banco";
            $boton = "Actualizar";

            $tipo_pagos = Tipo_pago::where('id','!=','1')->orderBy('nombre','asc')->get();
            
            return view('bancos.create',compact('tipo_pagos','banco','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>'',
            'tipo_pago_id'=>'',
            'tipo_cuenta_id'=>'',
            'num_cuenta'=>'',
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $banco = new Banco;
        $banco->tipo_pago_id = $r->tipo_pago_id;
        $banco->nombre = $r->nombre;
        $banco->tipo_cuenta_id = $r->tipo_cuenta_id;
        $banco->num_cuenta = $r->num_cuenta;
        $banco->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('bancos.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('bancos.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $banco = Banco::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre'=>'',
            'tipo_pago_id'=>'',
            'tipo_cuenta_id'=>'',
            'num_cuenta'=>'',
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $banco->tipo_pago_id = $r->tipo_pago_id;
        $banco->nombre = $r->nombre;
        $banco->tipo_cuenta_id = $r->tipo_cuenta_id;
        $banco->num_cuenta = $r->num_cuenta;
        
        $banco->save();

        return redirect()->route('bancos.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_bancos')){
            $banco = Banco::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Banco';
            return view('bancos.show',compact('titulo','banco'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_bancos')){
            $banco = Banco::where('id',$id)->firstOrFail();
            try {
                $banco->delete($id);
                return redirect()->route('bancos.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('bancos.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_bancos(Request $r){
        $bancos = Banco::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('bancos'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $banco = new Banco;
        $banco->nombre = $r->nombre;
        $banco->save();

        $bancos = Banco::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('bancos'))->render();
        return response()->json(['options'=>$opciones]);
        
    }

    public function cargar_bancos(Request $r){
        $bancos = Banco::where('tipo_pago_id',$r->tipo_pago_id)->orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('bancos'))->render();
    	return response()->json(['options'=>$opciones]);
    }
}

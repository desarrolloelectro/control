<?php

namespace App\Http\Controllers;

use App\Usuario;
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

class Tipo_pagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'tipo_pagos';

        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_pagos = Tipo_pago::where('id','!=','0')->orderBy('nombre','asc')->get();
            $title = "Lista Tipo_pagos";
            return view('tipo_pagos.index', compact('title','tipo_pagos','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'tipo_pagos';

        if(Auth::user()->validar_permiso('con_areas')){
            $accion = url('tipo_pagos/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Tipo_pago";
            $boton = "Crear";
            return view('tipo_pagos.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'tipo_pagos';

        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_pago = Tipo_pago::where('id',$id)->firstOrFail();

            $accion = url("tipo_pagos/actualizar/{$tipo_pago->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Tipo_pago";
            $boton = "Actualizar";
            return view('tipo_pagos.create',compact('tipo_pago','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:tipo_pagos,nombre'],
        ],[
            'nombre.required'=>"El campo Estado es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $tipo_pago = new Tipo_pago;
        $tipo_pago->nombre = $r->nombre;
        $tipo_pago->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('tipo_pagos.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('tipo_pagos.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $tipo_pago = Tipo_pago::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('tipo_pagos', 'nombre')->ignore($tipo_pago->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $tipo_pago->nombre = $r->nombre;
        
        $tipo_pago->save();

        return redirect()->route('tipo_pagos.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_pago = Tipo_pago::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Tipo_pago';
            return view('tipo_pagos.show',compact('titulo','tipo_pago'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_areas')){
            $tipo_pago = Tipo_pago::where('id',$id)->firstOrFail();
            try {
                $tipo_pago->delete($id);
                return redirect()->route('tipo_pagos.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('tipo_pagos.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_tipo_pagos(Request $r){
        $tipo_pagos = Tipo_pago::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_pagos'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $tipo_pago = new Tipo_pago;
        $tipo_pago->nombre = $r->nombre;
        $tipo_pago->save();

        $tipo_pagos = Tipo_pago::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('tipo_pagos'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

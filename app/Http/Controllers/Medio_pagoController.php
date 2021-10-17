<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Medio_pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Medio_pagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "configuracion";
        $subcon = 'medio_pagos';

        if(Auth::user()->validar_permiso('con_areas')){
            $medio_pagos = Medio_pago::orderBy('id','asc')->get();
            $title = "Lista Medio_pagos";
            return view('medio_pagos.index', compact('title','medio_pagos','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "configuracion";
        $subcon = 'medio_pagos';

        if(Auth::user()->validar_permiso('con_areas')){
            $accion = url('medio_pagos/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Medio_pago";
            $boton = "Crear";
            return view('medio_pagos.create',compact('accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "configuracion";
        $subcon = 'medio_pagos';

        if(Auth::user()->validar_permiso('con_areas')){
            $medio_pago = Medio_pago::where('id',$id)->firstOrFail();

            $accion = url("medio_pagos/actualizar/{$medio_pago->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Medio_pago";
            $boton = "Actualizar";
            return view('medio_pagos.create',compact('medio_pago','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:medio_pagos,nombre'],
        ],[
            'nombre.required'=>"El campo Estado es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $medio_pago = new Medio_pago;
        $medio_pago->nombre = $r->nombre;
        $medio_pago->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('medio_pagos.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('medio_pagos.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $medio_pago = Medio_pago::where('id',$id)->firstOrFail();

        $this->validate($r,[
            'nombre' => Rule::unique('medio_pagos', 'nombre')->ignore($medio_pago->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        $medio_pago->nombre = $r->nombre;
        
        $medio_pago->save();

        return redirect()->route('medio_pagos.index')->with('mensaje', 'Registro actualizado con éxito!');
 
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_areas')){
            $medio_pago = Medio_pago::where('id',$id)->firstOrFail();
            $titulo = 'Detalle Medio_pago';
            return view('medio_pagos.show',compact('titulo','medio_pago'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('con_areas')){
            $medio_pago = Medio_pago::where('id',$id)->firstOrFail();
            try {
                $medio_pago->delete($id);
                return redirect()->route('medio_pagos.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('medio_pagos.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_medio_pagos(Request $r){
        $medio_pagos = Medio_pago::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('medio_pagos'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $medio_pago = new Medio_pago;
        $medio_pago->nombre = $r->nombre;
        $medio_pago->save();

        $medio_pagos = Medio_pago::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('medio_pagos'))->render();
        return response()->json(['options'=>$opciones]);
        
    }
}

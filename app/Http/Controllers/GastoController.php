<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Gasto;
use App\Gasto_detalle;
use App\Agencia;
use App\Usuario_agencia;
use App\Gasto_estado;
use App\Tipo_gasto;
use App\Area;
use App\Tipo_identificacion;
use App\Cotizacion;
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

class GastoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "gastos";
        $subcon = 'gastos';

        if(Auth::user()->validar_permiso('gast_access')){
            $gastos = null;

            if(Auth::user()->validar_permiso('gast_list_usu')){
                $gastos = Gasto::where('user_new',Auth::id())->orderBy('id','desc')->get();

            }
            if(Auth::user()->validar_permiso('gast_list_agen')){
                $gastos = Gasto::where('agencia_id',Auth::user()->agencia)->orderBy('id','desc')->get();

                $agencia_principal = Auth::user()->agencia;

                $usuario_agencia = Usuario_agencia::where('usuario_id',Auth::id())->first();
                if($usuario_agencia != null){
                    $lista_agencias = explode(",",$usuario_agencia->agencias);
                    foreach ($lista_agencias as $agencia_secundaria) {
                        if($agencia_secundaria != $agencia_principal){
                            $gastos2 = Gasto::where('agencia_id',$agencia_secundaria)->orderBy('id','desc')->get();
                            $gastos = $gastos->merge($gastos2);
                        }
                    }
                }


            }
            if(Auth::user()->validar_permiso('gast_list_com')){
                $gastos = Gasto::orderBy('id','desc')->get();
            }

            $title = "Lista Gastos";
            return view('gastos.index', compact('title','gastos','usuario','controlador','subcon'));        
        }else{
            return view('errors.access_denied', compact('title','usuario'));
        }

    }

    public function create()
    {
        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
        $fecha_sistema = $dateonly;

        $controlador = "gastos";
        $subcon = 'gastos';

        if(Auth::user()->validar_permiso('gast_new')){
            $accion = url('gastos/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Gasto";
            $boton = "Crear";

            $agencias = Agencia::orderBy('agennom','asc')->where('codagen','!=','.')->get();

            $usuario_agencia = Usuario_agencia::where('usuario_id',Auth::id())->first();
            $lista_agencias = array();
            if($usuario_agencia != null){
                $lista_agencias = explode(",",$usuario_agencia->agencias);
            }
            array_push($lista_agencias,Auth::user()->agencia);

            $areas = Area::orderBy('nombre','asc')->get();
            $tipo_identificaciones = Tipo_identificacion::orderBy('nombre','asc')->get();

            $estados = Gasto_estado::orderBy('nombre','asc')->where('id','1')->get();
            $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get()->take(0);
            $usuario = Usuario::where('coduser',Auth::id())->first();
            $bancos = Banco::orderBy('nombre','asc')->get()->take(0);
            $tipo_pagos = Tipo_pago::orderBy('id','asc')->get();

            return view('gastos.create',compact('tipo_pagos','bancos','usuario','areas','tipo_identificaciones','estados','tipo_gastos','agencias','lista_agencias','fecha_sistema','servicios','accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "gastos";
        $subcon = 'gastos';

        if(Auth::user()->validar_permiso('gast_access')){
            $gasto = Gasto::where('id',$id)->firstOrFail();
            $gasto_detalle = Gasto_detalle::where('gasto_id',$gasto->id)->get();
          
            $accion = url("gastos/actualizar/{$gasto->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Gasto";
            $boton = "Actualizar";

            $agencias = Agencia::orderBy('agennom','asc')->where('codagen','!=','.')->get();
            $usuario = Usuario::where('coduser',$gasto->user_new)->first();
            $tipo_pagos = Tipo_pago::orderBy('id','asc')->get();


            $usuario_agencia = Usuario_agencia::where('usuario_id',$usuario->coduser)->first();
            $lista_agencias = array();
            if($usuario_agencia != null){
                $lista_agencias = explode(",",$usuario_agencia->agencias);
            }
            array_push($lista_agencias,$usuario->agencia);

            $areas = Area::orderBy('nombre','asc')->get();
            $tipo_identificaciones = Tipo_identificacion::orderBy('nombre','asc')->get();

            $estados = null;
            if(Auth::user()->validar_permiso('gast_revertir')){
                $estados = Gasto_estado::orderBy('nombre','asc')->get();
            }else{
                if($gasto->estado_id != 1){
                    $estados = Gasto_estado::where('id',$gasto->estado_id)->orderBy('nombre','asc')->get();
                }else{
                    $estados = Gasto_estado::orderBy('nombre','asc')->get();
                }
            }


            $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get();

            $cotizacion = Cotizacion::where('id',$gasto->cotizacion_id)->first();
            $bancos = Banco::orderBy('nombre','asc')->get();

            return view('gastos.create',compact('tipo_pagos','bancos','cotizacion','usuario','areas','tipo_identificaciones','gasto_detalle','agencias','usuario_agencia','lista_agencias','estados','tipo_gastos','gasto','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied', compact('title','usuario'));
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'descripcion'=>'required',
            'agencia_id'=>'required',
            'tipo_gasto_id'=>'required',
            'area_id'=>'required',
            'tipo_identificacion_id'=>'required',
            'id_cotizacion'=>'required',
        ],[
            'descripcion.required'=>"El campo Detalle es requerido",
            'agencia_id.required'=>"El campo Agencia es requerido",
            'tipo_gasto_id.required'=>"El campo Tipo Gasto es requerido",
            'area_id.required'=>"El campo Área es requerido",
            'tipo_identificacion_id.required'=>"El campo Tipo Identificación es requerido",
            'id_cotizacion.required'=>"El campo Autorización / Cotización es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $gasto = new Gasto;
        $gasto->descripcion = $r->descripcion;
        $gasto->agencia_id = $r->agencia_id;
        $gasto->tipo_gasto_id = $r->tipo_gasto_id;
        $gasto->num_gasto = $r->num_gasto;

        $gasto->area_id = $r->area_id;
        $gasto->tipo_identificacion_id = $r->tipo_identificacion_id;
        $gasto->identificacion = $r->identificacion;
        $gasto->dv = $r->dv;
        $gasto->razon = $r->razon;
        $gasto->factura = $r->factura;
        $gasto->codigo = $r->codigo;

        if($r->valor_solicitado != null || $r->valor_solicitado != ''){
            $gasto->valor_solicitado = $r->valor_solicitado;
        }

        $gasto->estado_id = 1;
        if($r->id_cotizacion != '' && $r->id_cotizacion != 0){
            $gasto->cotizacion_id = $r->id_cotizacion;
        }

        $gasto->tipo_pago_id = $r->tipo_pago_id;
        if($r->tipo_pago_id == '2'){
            $gasto->banco_id = $r->banco_id;

        }

        $gasto->user_new = Auth::id();
        $gasto->date_new = $dateonly;
        $gasto->created_at = $datehour;
        $gasto->updated_at = $datehour;
        $gasto->user_update = Auth::id();

        $gasto->save();

        $nombre_array = $r->nombre;
        $archivo_array = $r->archivo;

        if($nombre_array != null){
            $n = count($nombre_array);
            for ($i = 0; $i < $n; $i++ ) {

                $detalle = new Gasto_detalle;
                $detalle->gasto_id = $gasto->id;
                $detalle->nombre = $nombre_array[$i];

                $archivo = $archivo_array[$i];
               
                $file = $archivo;
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture = date('YmdHis').'-'.$filename;
                $file->move(public_path('uploads/gastos'), $picture);

                //$nombre_archivo = $archivo->store('','local');
                $detalle->urlarchivo = $picture;
           
                $detalle->date_new = $dateonly;
                $detalle->created_at = $datehour;
                $detalle->updated_at = $datehour;
                $detalle->user_new = Auth::id();
                $detalle->user_update = Auth::id();

                $detalle->save();
            }
        }

        return redirect()->route('gastos.index')->with('mensaje', 'Registro ingresado con éxito!');
       

    }    
    public function update(Request $r, $id)
    {
        $gasto = Gasto::where('id',$id)->firstOrFail();
        $estado_actual = $gasto->estado_id;

        /**$this->validate($r,[
            'descripcion'=>'required',
            'agencia_id'=>'required',
            'tipo_gasto_id'=>'required',
            'area_id'=>'required',
            'tipo_identificacion_id'=>'required',
        ],[
            'descripcion.required'=>"El campo Detalle es requerido",
            'agencia_id.required'=>"El campo Agencia es requerido",
            'tipo_gasto_id.required'=>"El campo Tipo Gasto es requerido",
            'area_id.required'=>"El campo Área es requerido",
            'tipo_identificacion_id.required'=>"El campo Tipo Identificación es requerido",
        ]);**/

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        if(Auth::user()->validar_permiso('gast_edit_gast') && $gasto->estado_id == 1){
            $gasto->descripcion = $r->descripcion;
            $gasto->agencia_id = $r->agencia_id;
            $gasto->tipo_gasto_id = $r->tipo_gasto_id;
            $gasto->num_gasto = $r->num_gasto;
            $gasto->area_id = $r->area_id;
            $gasto->tipo_identificacion_id = $r->tipo_identificacion_id;
            $gasto->identificacion = $r->identificacion;
            $gasto->dv = $r->dv;
            $gasto->razon = $r->razon;
            $gasto->factura = $r->factura;            
            $gasto->codigo = $r->codigo;
            if($r->valor_solicitado != null || $r->valor_solicitado != ''){
                $gasto->valor_solicitado = $r->valor_solicitado;
            }

            $gasto->tipo_pago_id = $r->tipo_pago_id;
            if($r->tipo_pago_id == '2'){
                $gasto->banco_id = $r->banco_id;

            }

        }

        if(Auth::user()->validar_permiso('gast_obs_auditoria') && $gasto->estado_id == 1){
            $gasto->obs_auditoria = $r->obs_auditoria;
            $gasto->tipo_doc_audi = $r->tipo_doc_audi;
            $gasto->num_doc_audi = $r->num_doc_audi;
        }

        if(Auth::user()->validar_permiso('gast_obs_revisoria')){
            $gasto->obs_revisoria = $r->obs_revisoria;
        }

        if(Auth::user()->validar_permiso('gast_valor') && $gasto->estado_id == 1){
            if($r->valor_autorizado != null || $r->valor_autorizado != ''){
                $gasto->valor_autorizado = $r->valor_autorizado;
            }
        }

        if((Auth::user()->validar_permiso('gast_estados') && $gasto->estado_id == 1)){
            if($r->estado_id == '2'){
                $gasto->user_autoriza = Auth::id();
                $gasto->date_autoriza = $datehour;
            }
        }

        if((Auth::user()->validar_permiso('gast_estados') && $gasto->estado_id == 1) || Auth::user()->validar_permiso('gast_revertir')){
            $gasto->estado_id = $r->estado_id;
        }

        if(Auth::user()->validar_permiso('gast_cargar_cot') && $gasto->estado_id == 1){
            if($r->id_cotizacion != '' && $r->id_cotizacion != null){
                $gasto->cotizacion_id = $r->id_cotizacion;
            }
        }

        $gasto->updated_at = $datehour;
        $gasto->user_update = Auth::id();

        $gasto->save();

        $nombre_array = $r->nombre;
        $archivo_array = $r->archivo;

        if($nombre_array != null){
            $n = count($nombre_array);
            for ($i = 0; $i < $n; $i++ ) {

                $detalle = new Gasto_detalle;
                $detalle->gasto_id = $gasto->id;
                $detalle->nombre = $nombre_array[$i];

                $archivo = $archivo_array[$i];
               
                $file = $archivo;
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture = date('YmdHis').'-'.$filename;
                $file->move(public_path('uploads/gastos'), $picture);

                //$nombre_archivo = $archivo->store('','local');
                $detalle->urlarchivo = $picture;
           
                $detalle->date_new = $dateonly;
                $detalle->created_at = $datehour;
                $detalle->updated_at = $datehour;
                $detalle->user_new = Auth::id();
                $detalle->user_update = Auth::id();

                $detalle->save();
            }
        }

        //************ ACTUALIZAR ARCHIVOS ANTIGUOS *************** */

        $id_tabla = $r->id_tabla;
        $nombre_tabla = $r->nombre_tabla;
        $archivo_tabla = $r->archivo_tabla;

        if($id_tabla != null){
            $n = count($id_tabla);
            for ($i = 0; $i < $n; $i++ ) {

                $detalle = Gasto_detalle::where('id',$id_tabla[$i])->first();
                if($detalle != null){
                    $detalle->nombre = $nombre_tabla[$i];

                    if(Auth::user()->validar_permiso('gast_edit_archivos') && $estado_actual == 1){

                        $archivo_antiguo = null;

                        if(isset($archivo_tabla[$i])){
                            $archivo_antiguo = $archivo_tabla[$i];
                        }
                        
                        if($archivo_antiguo != null){
                            $file = $archivo_antiguo;
                            $filename = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $picture = date('YmdHis').'-'.$filename;
                            $file->move(public_path('uploads/gastos'), $picture);
        
                            //$nombre_archivo = $archivo->store('','local');
                            $detalle->urlarchivo = $picture;
                        }

                    }
                    
                
                    $detalle->updated_at = $datehour;
                    $detalle->user_update = Auth::id();
    
                    $detalle->save();
                }
                
            }
        }

        return redirect("gastos/{$gasto->id}/editar")->with('mensaje', 'Actualización realizada con éxito!');


    }

    public function show($id)
    {
        $controlador = "gastos";
        $subcon = 'gastos';

        if(Auth::user()->validar_permiso('gast_access')){
            $gasto = Gasto::where('id',$id)->firstOrFail();
            $gasto_detalle = Gasto_detalle::where('gasto_id',$gasto->id)->get();
          
            $accion = url("gastos/actualizar/{$gasto->id}");
            $metodo = method_field('PUT');
            $titulo = "Detalle Gasto";
            $boton = "Actualizar";

            $agencias = Agencia::orderBy('agennom','asc')->where('codagen','!=','.')->get();

            $usuario_agencia = Usuario_agencia::where('usuario_id',Auth::id())->first();
            $lista_agencias = array();
            if($usuario_agencia != null){
                $lista_agencias = explode(",",$usuario_agencia->agencias);
            }
            array_push($lista_agencias,Auth::user()->agencia);

            $areas = Area::orderBy('nombre','asc')->get();
            $tipo_identificaciones = Tipo_identificacion::orderBy('nombre','asc')->get();

            $estados = Gasto_estado::orderBy('nombre','asc')->get();
            $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get();
            $usuario = Usuario::where('coduser',$gasto->user_new)->first();

            $cotizacion = Cotizacion::where('id',$gasto->cotizacion_id)->first();

            return view('gastos.show',compact('cotizacion','usuario','areas','tipo_identificaciones','gasto_detalle','agencias','usuario_agencia','lista_agencias','estados','tipo_gastos','gasto','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied', compact('title','usuario'));
        }

    }

    public function destroy($id)
    {

        if(Auth::user()->validar_permiso('gast_anular_gast')){
            $gasto = Gasto::where('id',$id)->firstOrFail();
            try {
                if($gasto->estado_id == 1){
                    $gasto->estado_id = 3;
                    $gasto->save();
                    return redirect()->route('gastos.index')->with('mensaje', 'Registro anulado con éxito!');
                }else{
                    return redirect()->route('gastos.index')->with('alerta', 'No se pudo anular el registro!');
                }
                
            } 
            catch(QueryException $e) {
                return redirect()->route('gastos.index')->with('alerta', 'No se pudo anular el registro!');
            }          
        }else{
            return view('errors.access_denied', compact('title','usuario'));
        }        

    }

    public function actualizar_gastos(Request $r){
        $gastos = Gasto::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('gastos'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $gasto = new Gasto;
        $gasto->nombre = $r->nombre;
        $gasto->save();

        $gastos = Gasto::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('gastos'))->render();
        return response()->json(['options'=>$opciones]);
        
    }

    public function eliminar_gasto(Request $r)
    {    
        if(Auth::user()->validar_permiso('gast_eliminar_archivos')){
            $gasto_detalle = Gasto_detalle::findOrFail($r->id);
            try {
                $gasto_detalle->delete($r->id);
                //return back()->with('mensaje', 'Registro eliminado con éxito!');
    	        return response()->json(['status'=>'success','mensaje'=>'Registro eliminado con éxito!']);
            } 
            catch(QueryException $e) {
    	        return response()->json(['status'=>'error','mensaje'=>'No se pudo eliminar el registro!']);

            } 
        }else{
            return view('errors.access_denied');
        }
    }  

    public function cargar_tipo_gastos(Request $r){
        $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get();
        $agencia_id = $r->agencia_id;
        $opciones = view('cargar_select',compact('tipo_gastos','agencia_id'))->render();
    	return response()->json(['options'=>$opciones]);
    }

}

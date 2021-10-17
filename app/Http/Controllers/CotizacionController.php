<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Cotizacion;
use App\Cotizacion_detalle;
use App\Agencia;
use App\Usuario_agencia;
use App\Cotizacion_estado;
use App\Cotizacion_soporte;
use App\Tipo_gasto;
use App\Tipo_gasto_agencia;
use App\Gasto;
use App\Gasto_estado;
use App\Banco;
use App\Tipo_pago;
use App\Area;
use App\Rol;
use App\Envio_correo;
use App\Revisoria_estado;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Intervention\Image\ImageManagerStatic as Image;
use Auth;

use PHPMailer\PHPMailer;

use App\Mail\EnviarEmail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

set_time_limit(0);
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
ini_set('upload_max_filesize','500M');
ini_set('post_max_size','500M');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class CotizacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "cotizaciones";
        $subcon = 'cotizaciones';
        $cotizaciones = null;

        $valor = "";
        if(isset($_GET['search'])){
            $valor = $_GET['search'];
        }

        if(Auth::user()->validar_permiso('con_access')){

            $cotizaciones = Cotizacion::query();
            $cotizaciones = $cotizaciones->select('cotizaciones.*')
            ->leftJoin('toolset_perf.agencia as db1','cotizaciones.agencia_id','db1.codagen')
            ->leftJoin('tipo_gastos as db2','cotizaciones.tipo_gasto_id','db2.id')
            ->leftJoin('cotizacion_estados as db3','cotizaciones.estado_id','db3.id')
            ->leftJoin('gasto_estados as db4','cotizaciones.gasto_estado_id','db4.id');

            $cotizaciones = $cotizaciones->where(function ($query) use ($valor){
                $query->where('cotizaciones.id', 'LIKE', '%' . $valor . '%')
                ->orWhere('cotizaciones.descripcion', 'LIKE', '%' . $valor . '%')
                ->orWhere('cotizaciones.agencia_id', 'LIKE', '%' . $valor . '%')
                ->orWhere('cotizaciones.num_gasto', 'LIKE', '%' . $valor . '%')
                ->orWhere('db1.agennom', 'LIKE', '%' . $valor . '%')
                ->orWhere('db2.nombre', 'LIKE', '%' . $valor . '%')
                ->orWhere('db2.tipo', 'LIKE', '%' . $valor . '%')
                ->orWhere('db3.nombre', 'LIKE', '%' . $valor . '%')
                ->orWhere('db4.nombre', 'LIKE', '%' . $valor . '%');
            });


            if(Auth::user()->validar_permiso('con_list_usu') && !Auth::user()->validar_permiso('con_list_com')){
                //$cotizaciones = Cotizacion::where('user_new',Auth::id())->orderBy('id','desc')->get();
                $cotizaciones = $cotizaciones->where('cotizaciones.user_new',Auth::id());
            }
            if(Auth::user()->validar_permiso('con_list_agen') && !Auth::user()->validar_permiso('con_list_com')){
                
                $usuario_agencia = Usuario_agencia::where('usuario_id',Auth::id())->first();

                $cotizaciones = $cotizaciones->where(function ($query) use ($usuario_agencia){

                    $query->where('agencia_id',Auth::user()->agencia);
                    if($usuario_agencia != null){
                        $lista_agencias = explode(",",$usuario_agencia->agencias);
                        foreach ($lista_agencias as $agencia_secundaria) {
                            $query = $query->orWhere('cotizaciones.agencia_id',$agencia_secundaria);
                        }
                    }
                    
                });
            }

            if(Auth::user()->validar_permiso('con_list_com')){

                $rol = Rol::where('id',Auth::user()->nivel_control)->firstOrFail();
                $agencias = explode(",",$rol->agencias);

                foreach ($agencias as $fila) {
                    $cotizaciones = $cotizaciones->where('cotizaciones.agencia_id','!=',$fila);
                }
            }


            $cotizaciones = $cotizaciones->orderBy('cotizaciones.id','desc')->paginate(50);

            if($valor != ''){
                $cotizaciones->appends(['search' => $valor]);

            }
            $url_paginacion = route('cotizaciones.index');

            $title = "Lista Autorizaciones / Gastos";
            return view('cotizaciones.index', compact('valor','url_paginacion','title','cotizaciones','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
        $fecha_sistema = $dateonly;

        $controlador = "cotizaciones";
        $subcon = 'cotizaciones';

        if(Auth::user()->validar_permiso('cot_new')){
            $accion = url('cotizaciones/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Autorización / Gasto";
            $boton = "Crear";

            $agencias = Agencia::orderBy('agennom','asc')->where('codagen','!=','.')->where('activo','1')->get();

            

            $usuario_agencia = Usuario_agencia::where('usuario_id',Auth::id())->first();
            $lista_agencias = array();
            if($usuario_agencia != null){
                $lista_agencias = explode(",",$usuario_agencia->agencias);
            }
            array_push($lista_agencias,Auth::user()->agencia);

            $estados = Cotizacion_estado::orderBy('nombre','asc')->where('id','1')->get();

            $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get()->take(0);
            $areas = Area::orderBy('nombre','asc')->get();
            $gasto_estados = Gasto_estado::orderBy('nombre','asc')->where('id','1')->get();
            $revisoria_estados = Revisoria_estado::orderBy('id','asc')->get();
            $bancos = Banco::orderBy('nombre','asc')->get()->take(0);
            $tipo_pagos = Tipo_pago::orderBy('id','asc')->get();


            $usuario = Usuario::where('coduser',Auth::id())->first();
            return view('cotizaciones.create',compact('usuario','revisoria_estados','gasto_estados','areas','bancos','tipo_pagos','estados','tipo_gastos','agencias','lista_agencias','fecha_sistema','accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {        
        $controlador = "cotizaciones";
        $subcon = 'cotizaciones';

        if(Auth::user()->validar_permiso('cot_edit_access')){
            $cotizacion = Cotizacion::where('id',$id)->firstOrFail();
            $cotizacion_detalle = Cotizacion_detalle::where('cotizacion_id',$cotizacion->id)->get();
            $cotizacion_soporte = Cotizacion_soporte::where('cotizacion_id',$cotizacion->id)->get();
          
            $accion = url("cotizaciones/actualizar/{$cotizacion->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Autorización / Gasto";
            $boton = "Actualizar";

            $agencias = Agencia::orderBy('agennom','asc')->where('codagen','!=','.')->where('activo','1')->get();
            $usuario = Usuario::where('coduser',$cotizacion->user_new)->first();
            $lista_agencias = array();
            
            if($usuario != null){
                $usuario_agencia = Usuario_agencia::where('usuario_id',$usuario->coduser)->first();
                if($usuario_agencia != null){
                    $lista_agencias = explode(",",$usuario_agencia->agencias);
                }
            }

            if($usuario != null){
                array_push($lista_agencias,$usuario->agencia);
            }

            $estados = null;
            if(Auth::user()->validar_permiso('cot_revertir')){
                $estados = Cotizacion_estado::orderBy('nombre','asc')->get();
            }else{
                if($cotizacion->estado_id != 1){
                    $estados = Cotizacion_estado::where('id',$cotizacion->estado_id)->orderBy('id','asc')->get();
                }else{
                    $estados = Cotizacion_estado::orderBy('nombre','asc')->get();
                }
            }

            $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get();
            $areas = Area::orderBy('nombre','asc')->get();
            
            $gasto_estados = Gasto_estado::orderBy('id','asc')->get()->take(0);

            if((Auth::user()->validar_permiso('cot_estados_gast') && $cotizacion->gasto_estado_id != 2) || Auth::user()->validar_permiso('cot_revertir_gast')){
                $gasto_estados = Gasto_estado::orderBy('id','asc')->get();
            }

            $bancos = Banco::orderBy('nombre','asc')->get()->take(0);
            $tipo_pagos = Tipo_pago::orderBy('id','asc')->get();
            $revisoria_estados = Revisoria_estado::orderBy('id','asc')->get();

            if($cotizacion->revisoria_estado_id == '2' && !Auth::user()->validar_permiso('cot_revertir')){
                return view('errors.access_denied');
            }

            return view('cotizaciones.create',compact('usuario','revisoria_estados','cotizacion_soporte','areas','gasto_estados','bancos','tipo_pagos','cotizacion_detalle','agencias','usuario_agencia','lista_agencias','estados','tipo_gastos','cotizacion','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'descripcion'=>'required',
            'agencia_id'=>'required',
            'tipo_gasto_id'=>'required',
            'nombre'=>'required',
        ],[
            'descripcion.required'=>"El campo Detalle es requerido",
            'agencia_id.required'=>"El campo Agencia es requerido",
            'tipo_gasto_id.required'=>"El campo Tipo Gasto es requerido",
            'nombre.required'=>"Debe adjuntar al menos un archivo",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $cotizacion = new Cotizacion;
        $cotizacion->descripcion = $r->descripcion;
        $cotizacion->agencia_id = $r->agencia_id;
        $cotizacion->tipo_gasto_id = $r->tipo_gasto_id;
        $cotizacion->estado_id = 1;

        $cotizacion->user_new = Auth::id();
        $cotizacion->date_new = $dateonly;
        $cotizacion->created_at = $datehour;
        $cotizacion->updated_at = $datehour;
        $cotizacion->user_update = Auth::id();

        $cotizacion->save();

        $nombre_array = $r->nombre;
        $valor_array = $r->valor;
        $archivo_array = $r->archivo;

        if($nombre_array != null){
            $n = count($nombre_array);
            for ($i = 0; $i < $n; $i++ ) {

                $detalle = new Cotizacion_detalle;
                $detalle->cotizacion_id = $cotizacion->id;
                $detalle->nombre = $nombre_array[$i];
                $detalle->valor = $valor_array[$i];

                $archivo = $archivo_array[$i];
               
                //************** SUBIR ARCHIVO PESADO ************ */

                /**$file = $archivo;

                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture = date('YmdHis').'-'.$filename;
                $file->move(public_path('uploads/cotizaciones'), $picture);                
                $detalle->urlarchivo = $picture;**/

                //************* SUBIR ARCHIVO REDUCIDO ************** */

                $file = $archivo;

                if($file != null && $file != ''){
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $picture = date('YmdHis').'-'.$filename;

                    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif'){
                        $ruta=public_path('uploads/cotizaciones/'.$picture);                    
                        Image::make($file->getRealPath())
                            ->resize(1000,null, function ($constraint){ 
                                $constraint->aspectRatio();
                            })
                            ->save($ruta,72);
                    }else{
                        $file->move(public_path('uploads/cotizaciones'), $picture); 
                    }

                    $detalle->urlarchivo = $picture;
                }

                //*************************************************** */
           
                $detalle->date_new = $dateonly;
                $detalle->created_at = $datehour;
                $detalle->updated_at = $datehour;
                $detalle->user_new = Auth::id();
                $detalle->user_update = Auth::id();

                $detalle->save();
            }
        }

        return redirect()->route('cotizaciones.index')->with('mensaje', 'Registro ingresado con éxito!');
       

    }    
    public function update(Request $r, $id)
    {

        $cotizacion = Cotizacion::where('id',$id)->firstOrFail();
        $estado_actual = $cotizacion->estado_id;

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        if(Auth::user()->validar_permiso('cot_edit_cot') && $cotizacion->estado_id == 1){
            $cotizacion->descripcion = $r->descripcion;
            $cotizacion->agencia_id = $r->agencia_id;
            $cotizacion->tipo_gasto_id = $r->tipo_gasto_id;
        }
            
        if(Auth::user()->validar_permiso('cot_obs') && $cotizacion->estado_id == 1){
            $cotizacion->obs = $r->obs;
        }

        if(Auth::user()->validar_permiso('cot_estados') && ($cotizacion->estado_id == 1 || $cotizacion->estado_id == 6)){
            if($r->estado_id == '2'){
                $cotizacion->user_autoriza = Auth::id();
                $cotizacion->date_autoriza = $datehour;
            }
        }

        if((Auth::user()->validar_permiso('cot_estados') && ($cotizacion->estado_id == 1 || $cotizacion->estado_id == 6)) || Auth::user()->validar_permiso('cot_revertir')){
            $cotizacion->estado_id = $r->estado_id;
        }

        $cotizacion->updated_at = $datehour;
        $cotizacion->user_update = Auth::id();

        $cotizacion->save();

        $nombre_array = $r->nombre;
        $valor_array = $r->valor;
        $archivo_array = $r->archivo;

        if($nombre_array != null){
            $n = count($nombre_array);
            for ($i = 0; $i < $n; $i++ ) {

                $detalle = new Cotizacion_detalle;
                $detalle->cotizacion_id = $cotizacion->id;
                $detalle->nombre = $nombre_array[$i];
                $detalle->valor = $valor_array[$i];

                $archivo = $archivo_array[$i];

                //************* SUBIR ARCHIVO PESADO **************** */
               
                /**$file = $archivo;
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture = date('YmdHis').'-'.$filename;
                $file->move(public_path('uploads/cotizaciones'), $picture);
                $detalle->urlarchivo = $picture;**/

                //************* SUBIR ARCHIVO REDUCIDO ************** */

                $file = $archivo;

                if($file != null && $file != ''){
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $picture = date('YmdHis').'-'.$filename;

                    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif'){
                        $ruta=public_path('uploads/cotizaciones/'.$picture);                    
                        Image::make($file->getRealPath())
                            ->resize(1000,null, function ($constraint){ 
                                $constraint->aspectRatio();
                            })
                            ->save($ruta,72);
                    }else{
                        $file->move(public_path('uploads/cotizaciones'), $picture); 
                    }

                    $detalle->urlarchivo = $picture;
                }

                //*************************************************** */
           
                $detalle->date_new = $dateonly;
                $detalle->created_at = $datehour;
                $detalle->updated_at = $datehour;
                $detalle->user_new = Auth::id();
                $detalle->user_update = Auth::id();

                $detalle->save();
            }
        }

        //************ ACTUALIZAR COTIZACIONES ANTIGUAS *************** */


        $id_tabla = $r->id_tabla;
        $nombre_tabla = $r->nombre_tabla;
        $valor_tabla = $r->valor_tabla;
        $archivo_tabla = $r->archivo_tabla;
        $autorizado_tabla = $r->autorizado_tabla;


        if($id_tabla != null){
            $n = count($id_tabla);
            for ($i = 0; $i < $n; $i++ ) {

                $detalle = Cotizacion_detalle::where('id',$id_tabla[$i])->first();
                if($detalle != null){
                    $detalle->nombre = $nombre_tabla[$i];
                    $detalle->valor = $valor_tabla[$i];

                    if(Auth::user()->validar_permiso('cot_autorizar') && $estado_actual == 1){
                        if($cotizacion->estado_id == 2){
                            if(isset($autorizado_tabla) && $autorizado_tabla == $id_tabla[$i]){
                                $detalle->autorizado = 1;
                            }else{
                                $detalle->autorizado = 0;
                            }
                        }else{
                            $detalle->autorizado = 0;
                        }
                    }

                    if(Auth::user()->validar_permiso('cot_edit_archivos') && $estado_actual == 1){
                        $archivo_antiguo = null;

                        if(isset($archivo_tabla[$i])){
                            $archivo_antiguo = $archivo_tabla[$i];
                        }
                        
                        if($archivo_antiguo != null){

                            //************* SUBIR ARCHIVO PESADO ************** */

                            /**$file = $archivo_antiguo;
                            $filename = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $picture = date('YmdHis').'-'.$filename;
                            $file->move(public_path('uploads/cotizaciones'), $picture);        
                            $detalle->urlarchivo = $picture;**/

                            //************* SUBIR ARCHIVO REDUCIDO ************** */

                            $file = $archivo_antiguo;

                            if($file != null && $file != ''){
                                $filename = $file->getClientOriginalName();
                                $extension = $file->getClientOriginalExtension();
                                $picture = date('YmdHis').'-'.$filename;
            
                                if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif'){
                                    $ruta=public_path('uploads/cotizaciones/'.$picture);                    
                                    Image::make($file->getRealPath())
                                        ->resize(1000,null, function ($constraint){ 
                                            $constraint->aspectRatio();
                                        })
                                        ->save($ruta,72);
                                }else{
                                    $file->move(public_path('uploads/cotizaciones'), $picture); 
                                }
            
                                $detalle->urlarchivo = $picture;
                            }

                            //*************************************************** */
                        }
                    }
                
                    $detalle->updated_at = $datehour;
                    $detalle->user_update = Auth::id();
    
                    $detalle->save();
                }
                
            }
        }


        //**************** REGISTRAR INFORMACIÓN GASTO************/
       

        $cotizacion = Cotizacion::where('id',$id)->firstOrFail();
        $estado_gasto_anterior = $cotizacion->gasto_estado_id;
        
        $bloq_gasto = false;
        if($cotizacion->gasto_estado_id == 2){
            $bloq_gasto = true;
        }

        if($cotizacion->estado_id == 2){

            if(Auth::user()->validar_permiso('cot_creat_gast') && !$bloq_gasto){
                $cotizacion->tipo_gasto_id_gasto = $r->tipo_gasto_id_gasto;
                $cotizacion->num_gasto = $r->num_gasto;
                $cotizacion->area_id = $r->area_id;
                $cotizacion->factura = $r->factura;
                $cotizacion->codigo = $r->codigo;
                $cotizacion->valor_egreso = $r->valor_egreso;
                $cotizacion->descripcion_gasto = $r->descripcion_gasto;
                $cotizacion->tipo_pago_id = $r->tipo_pago_id;
                $cotizacion->banco_id = $r->banco_id;
            }

            if(Auth::user()->validar_permiso('cto_obs_aud_gast') && !$bloq_gasto){
                $cotizacion->obs_auditoria = $r->obs_auditoria;
            }
            if(Auth::user()->validar_permiso('cot_obs_rev_gast')){
                $cotizacion->obs_revisoria = $r->obs_revisoria;
                if($r->revisoria_estado_id != null){
                    $cotizacion->revisoria_estado_id = $r->revisoria_estado_id;
                }
            }


            if(Auth::user()->validar_permiso('cot_valor_gast') && !$bloq_gasto){
                $cotizacion->valor_autorizado = $r->valor_autorizado;
            }

            if($r->gasto_estado_id == 2 && $cotizacion->gasto_estado_id != 2){
                $cotizacion->user_autoriza_gasto = Auth::id();
                $cotizacion->date_autoriza_gasto = $datehour;
            }

            if((Auth::user()->validar_permiso('cot_estados_gast') && $cotizacion->gasto_estado_id != 2) || Auth::user()->validar_permiso('cot_revertir_gast') ){
                $cotizacion->gasto_estado_id = $r->gasto_estado_id;               
            }

            if($cotizacion->gasto_estado_id == '1' || $cotizacion->gasto_estado_id == null || $cotizacion->gasto_estado_id == ''){
                $cotizacion->gasto_estado_id = 1;
            }


            if($estado_gasto_anterior == '1' && $r->num_gasto != null && $r->num_gasto != '' && ($r->gasto_estado_id == '1' || $r->gasto_estado_id == null || $r->gasto_estado_id == '4')){
                $cotizacion->gasto_estado_id = 4;
            }
            
            $cotizacion->save();
        }

        //******************REGISTRAR NUEVOS ARCHIVOS *************** */

        if(Auth::user()->validar_permiso('cot_files_gast') && !$bloq_gasto){
            $nombre_sop_array = $r->nombre_sop;
            $archivo_sop_array = $r->archivo_sop;
    
            if($nombre_sop_array != null){
                $n = count($nombre_sop_array);
                for ($i = 0; $i < $n; $i++ ) {
    
                    $detalle = new Cotizacion_soporte;
                    $detalle->cotizacion_id = $cotizacion->id;
                    $detalle->nombre = $nombre_sop_array[$i];
    
                    $archivo_sop = $archivo_sop_array[$i];
                   
                    /**$file = $archivo_sop;
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $picture = date('YmdHis').'-'.$filename;
                    $file->move(public_path('uploads/gastos'), $picture);                        
                    $detalle->urlarchivo = $picture;**/

                    //************* SUBIR ARCHIVO REDUCIDO ************** */

                    $file = $archivo_sop;

                    if($file != null && $file != ''){
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $picture = date('YmdHis').'-'.$filename;

                        if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif'){
                            $ruta=public_path('uploads/gastos/'.$picture); 

                            Image::make($file->getRealPath())
                                ->resize(1000,null, function ($constraint){ 
                                    $constraint->aspectRatio();
                                })
                                ->save($ruta,72);
                            
                        }else{
                            $file->move(public_path('uploads/gastos'), $picture); 
                        }

                        $detalle->urlarchivo = $picture;
                    }

                    //*************************************************** */
               
                    $detalle->date_new = $dateonly;
                    $detalle->created_at = $datehour;
                    $detalle->updated_at = $datehour;
                    $detalle->user_new = Auth::id();
                    $detalle->user_update = Auth::id();
    
                    $detalle->save();
                }
            }
        }
        

        //************ ACTUALIZAR ARCHIVOS ANTIGUOS *************** */

        if(Auth::user()->validar_permiso('cot_edit_files_gast') && !$bloq_gasto){
            
            $id_sop_tabla = $r->id_sop_tabla;
            $nombre_sop_tabla = $r->nombre_sop_tabla;
            $archivo_sop_tabla = $r->archivo_sop_tabla;
    
            if($id_sop_tabla != null){
                $n = count($id_sop_tabla);
                for ($i = 0; $i < $n; $i++ ) {
    
                    $detalle = Cotizacion_soporte::where('id',$id_sop_tabla[$i])->first();
                    if($detalle != null){
                        $detalle->nombre = $nombre_sop_tabla[$i];

                        $archivo_sop_antiguo = null;
    
                        if(isset($archivo_sop_tabla[$i])){
                            $archivo_sop_antiguo = $archivo_sop_tabla[$i];
                        }
                        
                        if($archivo_sop_antiguo != null){


                            /**$file = $archivo_sop_antiguo;
                            $filename = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $picture = date('YmdHis').'-'.$filename;
                            $file->move(public_path('uploads/gastos'), $picture);                                
                            $detalle->urlarchivo = $picture;**/

                            $file = $archivo_sop_antiguo;

                            if($file != null && $file != ''){
                                $filename = $file->getClientOriginalName();
                                $extension = $file->getClientOriginalExtension();
                                $picture = date('YmdHis').'-'.$filename;
            
                                if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif'){
                                    $ruta=public_path('uploads/gastos/'.$picture);                    
                                    Image::make($file->getRealPath())
                                        ->resize(1000,null, function ($constraint){ 
                                            $constraint->aspectRatio();
                                        })
                                        ->save($ruta,72);
                                }else{
                                    $file->move(public_path('uploads/gastos'), $picture); 
                                }
            
                                $detalle->urlarchivo = $picture;
                            }

                        }
    
                        $detalle->updated_at = $datehour;
                        $detalle->user_update = Auth::id();
        
                        $detalle->save();
                    }
                    
                }
            }

        }

        $enlace = route("cotizaciones.edit",['id'=>$cotizacion->id]);
        
        //return redirect("cotizaciones/{$cotizacion->id}/editar")->with('mensaje', 'Actualización realizada con éxito!');
        return redirect()->route('cotizaciones.index')->with("mensaje", "¡El registro <strong><a href = '{$enlace}'>{$cotizacion->id}</a></strong> ha sido actualizado correctamente!");


    }


    public function show($id)
    {        
        $controlador = "cotizaciones";
        $subcon = 'cotizaciones';

        if(Auth::user()->validar_permiso('con_access')){
            $cotizacion = Cotizacion::where('id',$id)->firstOrFail();
            $cotizacion_detalle = Cotizacion_detalle::where('cotizacion_id',$cotizacion->id)->get();
            $cotizacion_soporte = Cotizacion_soporte::where('cotizacion_id',$cotizacion->id)->get();
          
            $titulo = "Detalle Autorización / Gasto";

            $agencias = Agencia::orderBy('agennom','asc')->where('codagen','!=','.')->get();
            $usuario = Usuario::where('coduser',$cotizacion->user_new)->first();
            $lista_agencias = array();
            
            if($usuario != null){
                $usuario_agencia = Usuario_agencia::where('usuario_id',$usuario->coduser)->first();
                if($usuario_agencia != null){
                    $lista_agencias = explode(",",$usuario_agencia->agencias);
                }
            }

            if($usuario != null){
                array_push($lista_agencias,$usuario->agencia);
            }

            $estados = null;
            if(Auth::user()->validar_permiso('cot_revertir')){
                $estados = Cotizacion_estado::orderBy('nombre','asc')->get();
            }else{
                if($cotizacion->estado_id != 1){
                    $estados = Cotizacion_estado::where('id',$cotizacion->estado_id)->orderBy('id','asc')->get();
                }else{
                    $estados = Cotizacion_estado::orderBy('nombre','asc')->get();
                }
            }

            $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get();
            $areas = Area::orderBy('nombre','asc')->get();
            
            $gasto_estados = Gasto_estado::orderBy('id','asc')->get()->take(0);

            if((Auth::user()->validar_permiso('cot_estados_gast') && $cotizacion->gasto_estado_id != 2) || Auth::user()->validar_permiso('cot_revertir_gast')){
                $gasto_estados = Gasto_estado::orderBy('id','asc')->get();
            }

            $bancos = Banco::orderBy('nombre','asc')->get()->take(0);
            $tipo_pagos = Tipo_pago::orderBy('id','asc')->get();
            $revisoria_estados = Revisoria_estado::orderBy('id','asc')->get();

            return view('cotizaciones.show',compact('revisoria_estados','cotizacion_soporte','areas','gasto_estados','bancos','tipo_pagos','cotizacion_detalle','agencias','usuario_agencia','lista_agencias','estados','tipo_gastos','cotizacion','titulo','usuario','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {

        if(Auth::user()->validar_permiso('cot_anular_cot')){
            $cotizacion = Cotizacion::where('id',$id)->firstOrFail();
            try {
                if($cotizacion->estado_id == 1){
                    $cotizacion->estado_id = 3;
                    $cotizacion->save();
                    return redirect()->route('cotizaciones.index')->with('mensaje', 'Registro anulado con éxito!');
                }else{
                    return redirect()->route('cotizaciones.index')->with('alerta', 'No se pudo anular el registro!');
                }
                
            } 
            catch(QueryException $e) {
                return redirect()->route('cotizaciones.index')->with('alerta', 'No se pudo anular el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_cotizaciones(Request $r){
        $cotizaciones = Cotizacion::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('cotizaciones'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $cotizacion = new Cotizacion;
        $cotizacion->nombre = $r->nombre;
        $cotizacion->save();

        $cotizaciones = Cotizacion::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('cotizaciones'))->render();
        return response()->json(['options'=>$opciones]);
        
    }

    public function eliminar_cotizacion(Request $r)
    {   
        
        if(Auth::user()->validar_permiso('cot_eliminar_archivos')){
            $cotizacion_detalle = Cotizacion_detalle::findOrFail($r->id);
            try {
                $cotizacion_detalle->delete($r->id);
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

    public function eliminar_gasto(Request $r)
    {   
        
        if(Auth::user()->validar_permiso('cot_del_files_gast')){
            $cotizacion_soporte = Cotizacion_soporte::findOrFail($r->id);
            try {
                $cotizacion_soporte->delete($r->id);
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

    public function cargar_cotizaciones(Request $r){
        $cotizaciones = Cotizacion::where('agencia_id',$r->agencia_id)->where('estado_id','2')->orderBy('id','desc')->get();
        $cotizaciones_asignadas = Gasto::select('cotizacion_id')->where('cotizacion_id',"!=",null)->get();

        $contenido = array();
        foreach ($cotizaciones_asignadas as $fila) {
            array_push($contenido,$fila->cotizacion_id);
        }
        
        $opciones = view('cargar_tabla',compact('cotizaciones','contenido'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function buscar(Request $r){

        $id = $r->id;

        $descripcion = "";
        $agencia = "";
        $tipo_gasto = "";
        $estado = "";
        $valor = "";
        $fecha = "";

        $cotizacion = Cotizacion::where('id',$id)->first();
        if($cotizacion != null){
            $descripcion = $cotizacion->descripcion;
            $agencia = $cotizacion->agencia != null ? $cotizacion->agencia->agennom : "";
            $tipo_gasto = $cotizacion->tipo_gasto != null ? $cotizacion->tipo_gasto->tipo.' :: '.$cotizacion->tipo_gasto->nombre : "";
            $estado = $cotizacion->estado != null ? $cotizacion->estado->nombre : "";
            $valor = $cotizacion->valor_autorizado($cotizacion->id);
            $fecha = $cotizacion->created_at;
        }

        return response()->json(['descripcion'=>$descripcion,'agencia'=>$agencia,'tipo_gasto'=>$tipo_gasto,
        'estado'=>$estado,'valor'=>$valor,'fecha'=>$fecha]);
    }

    public function enviar($id){

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
        
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion_detalle = Cotizacion_detalle::where('cotizacion_id',$cotizacion->id)->get();
        $fecha_envio = $datehour;
        $usuario = Usuario::where('coduser',$cotizacion->user_new)->first();
        $envio_correos = Envio_correo::get();
        $correo_usuario = Auth::user()->correo;
        
        if($envio_correos->count() > 0){           

            $correos = array();
            foreach ($envio_correos as $fila) {
                array_push($correos,$fila->correo);
            }
            array_push($correos,$correo_usuario);

            //return view('emails.enviar',compact('cotizacion','fecha_envio','usuario','cotizacion_detalle'));
    
            Mail::to($correos,'TOOLSET CONTROL '.$fecha_envio)->send(new EnviarEmail($cotizacion,$fecha_envio,$usuario,$cotizacion_detalle));

            if( count(Mail::failures()) > 0 ) {

                return redirect()->route('cotizaciones.edit',['id'=>$cotizacion->id])->with('alerta', 'Hubo un error al momento de enviar el mensaje. Intente nuevamente!');
                } else {
                return redirect()->route('cotizaciones.edit',['id'=>$cotizacion->id])->with('mail', 'Mensaje enviado con éxito!');
                }
            
        }else{
            return redirect()->route('cotizaciones.edit',['id'=>$cotizacion->id])->with('alerta', 'No existen correos registrados!');
        }

        
    }

    public function script_gastos(){
        $cotizaciones = Cotizacion::where('estado_id','2')->where('gasto_estado_id','1')->get();
        $cadena = "";
        foreach ($cotizaciones as $cotizacion) {
            if($cotizacion->num_gasto != null && $cotizacion->num_gasto != ''){
                $cotizacion->gasto_estado_id = 4;
                $cotizacion->save();

                $cadena = $cadena."COTIZACION #".$cotizacion->id." :: ";
            }
        }

        return redirect()->route('cotizaciones.index')->with('mensaje', "Actualización realizada con éxito! ".$cadena);
    }

    public function script_revertir(){

        $cotizaciones = Cotizacion::where('estado_id','2')->where('gasto_estado_id','4')->get();
        $cadena = "";
        foreach ($cotizaciones as $cotizacion) {
            if($cotizacion->num_gasto == null || $cotizacion->num_gasto == ''){
                $cotizacion->gasto_estado_id = 1;
                $cotizacion->save();

                $cadena = $cadena."COTIZACION #".$cotizacion->id." :: ";
            }
        }

        return redirect()->route('cotizaciones.index')->with('mensaje', "Actualización realizada con éxito! ".$cadena);
    }


}

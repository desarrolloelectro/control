<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Dia_iva;
use App\Medio_pago;

use App\Tipo_identificacion;
use App\Tipo_factura;
use App\Tipo_documento;
use App\Categoria;
use App\Genero;
use App\Unidad;
use App\Iva_estado;
use App\Historial;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManagerStatic as Image;
use Auth;

set_time_limit(0);
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
ini_set('upload_max_filesize','500M');
ini_set('post_max_size','500M');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class Dia_ivaController extends Controller
{

    protected $mensaje_errores = "";
    protected $contador_errores = 0;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "dia_ivas";
        $subcon = 'dia_ivas';

        if(Auth::user()->validar_permiso('dia_access_nov')){

            /**$valor = "";
            if(isset($_GET['search'])){
                $valor = $_GET['search'];
            }**/

            //***** CAMBIO TEMPORAL */
            $valor = "";
            if(isset($_GET['search'])){
                $valor = $_GET['search'];
                session(['valor_session' => $valor]);
            }
            $valor = session('valor_session');

            $medio_pago_id = "";
            if(isset($_GET['medio_pago_id'])){
                $medio_pago_id = $_GET['medio_pago_id'];
                session(['medio_pago_session' => $medio_pago_id]);
            }
            $medio_pago_id = session('medio_pago_session');

            //********************* */
            
            $porciones = explode("-", $valor);


            if(isset($porciones[1])){
                
                //$dia_ivas = Dia_iva::where('tipodoc',$porciones[0])->where('numdoc',$porciones[1])->orderBy('id','desc')->paginate(50);

                $dia_ivas = Dia_iva::query();
                $dia_ivas = $dia_ivas->select('dia_ivas.*')
                ->leftJoin('tipo_documentos as db2','dia_ivas.tipodoc','db2.id')
                ->where('db2.codigo',$porciones[0])
                ->where('dia_ivas.numdoc',$porciones[1]);

                if($medio_pago_id != '0' && $medio_pago_id != '' && $medio_pago_id != null){
                    $dia_ivas = $dia_ivas->where('dia_ivas.mediopago',$medio_pago_id);
                }

                $dia_ivas = $dia_ivas->orderBy('dia_ivas.id','desc')->paginate(50);


            }else{

                $dia_ivas = Dia_iva::query();
                $dia_ivas = $dia_ivas->select('dia_ivas.*')
                ->leftJoin('tipo_documentos as db2','dia_ivas.tipodoc','db2.id')
                ->where(function ($query) use ($valor){
                    $query->where('dia_ivas.identificacion', 'LIKE', '%' . $valor . '%')
                    ->orWhere('dia_ivas.id', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('tipofac', 'LIKE', '%' . $valor . '%')              
                    ->orWhere('dia_ivas.numdoc', 'LIKE', '%' . $valor . '%')             
                    ->orWhere('db2.depto', 'LIKE', '%' . $valor . '%')              
                    ->orWhere('db2.codigo', 'LIKE', '%' . $valor . '%')              
                    ->orWhere('db2.ciudad', 'LIKE', '%' . $valor . '%');              
                    //->orWhere('lugar', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('fecha', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('descripcion', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('mediopago', 'LIKE', '%' . $valor . '%')    ;          
                });

                if($medio_pago_id != '0' && $medio_pago_id != '' && $medio_pago_id != null){
                    $dia_ivas = $dia_ivas->where('dia_ivas.mediopago',$medio_pago_id);
                }

                $dia_ivas = $dia_ivas->orderBy('dia_ivas.id','desc')->paginate(50);
            }
                       

            $title = "Lista Día sin IVA Noviembre";
            $medio_pagos = Medio_pago::orderBy('id','asc')->get();

            if($valor != '' && $valor != null){
                $dia_ivas->appends(['search' => $valor]);
            }

            if($medio_pago_id != '' && $medio_pago_id != null){
                $dia_ivas->appends(['medio_pago_id' => $medio_pago_id]);
            }

            $url_paginacion = route('dia_ivas.index');

            return view('dia_ivas.index', compact('medio_pagos','valor','medio_pago_id','url_paginacion','title','dia_ivas','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "dia_ivas";
        $subcon = 'dia_ivas';

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
        $fecha_sistema = $dateonly;

        if(Auth::user()->validar_permiso('dia_create_nov')){
            $accion = url('dia_ivas/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Día sin IVA Noviembre";
            $boton = "Crear";

            $medio_pagos = Medio_pago::orderBy('id','asc')->get();

            $tipo_identificaciones = Tipo_identificacion::orderBy('id','asc')->get();
            $tipo_facturas = Tipo_factura::orderBy('id','asc')->get();
            $tipo_documentos = Tipo_documento::orderBy('id','asc')->get();
            $categorias = Categoria::orderBy('id','asc')->get();
            $generos = Genero::orderBy('id','asc')->get();
            $unidades = Unidad::orderBy('id','asc')->get();
            $iva_estados = Iva_estado::orderBy('id','asc')->get();

            return view('dia_ivas.create',compact('tipo_identificaciones','tipo_facturas','tipo_documentos',
            'categorias','generos','unidades','iva_estados','fecha_sistema','medio_pagos',
            'accion','metodo','titulo','boton','controlador','subcon'));       

        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "dia_ivas";
        $subcon = 'dia_ivas';

        if(Auth::user()->validar_permiso('dia_edit_nov')){

            $dia_iva = Dia_iva::where('id',$id)->firstOrFail();

            if($dia_iva->caja2_estado_id == 6 && !Auth::user()->validar_permiso('dia_revertir')){
                return view('errors.access_denied');
            }

            $medio_pagos = Medio_pago::orderBy('id','asc')->get();

            $tipo_identificaciones = Tipo_identificacion::orderBy('id','asc')->get();
            $tipo_facturas = Tipo_factura::orderBy('id','asc')->get();
            $tipo_documentos = Tipo_documento::orderBy('id','asc')->get();
            $categorias = Categoria::orderBy('id','asc')->get();
            $generos = Genero::orderBy('id','asc')->get();
            $unidades = Unidad::orderBy('id','asc')->get();
            $iva_estados = Iva_estado::orderBy('id','asc')->get();
            $historicos = Historial::where('dia_iva_id',$dia_iva->id)->orderBy('id','desc')->get();

            $accion = url("dia_ivas/actualizar/{$dia_iva->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Día sin IVA Noviembre";
            $boton = "Actualizar";
            return view('dia_ivas.create',compact('titulo','historicos','tipo_identificaciones','tipo_facturas','tipo_documentos',
            'categorias','generos','unidades','iva_estados','medio_pagos',
            'dia_iva','accion','metodo','boton','controlador','subcon'));  

        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>'required',
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $dia_iva = new Dia_iva;

        $dia_iva->tipoid = $r->tipoid;
        $dia_iva->identificacion = $r->identificacion;
        $dia_iva->nombre = $r->nombre;
        $dia_iva->tipofac = $r->tipofac;
        $dia_iva->tipodoc = $r->tipodoc;
        $dia_iva->numdoc = $r->numdoc;
        $dia_iva->fecha = $r->fecha;
        $dia_iva->categoria = $r->categoria;
        $dia_iva->genero = $r->genero;
        $dia_iva->cantidad = $r->cantidad;
        $dia_iva->unidad = $r->unidad;
        $dia_iva->descripcion = $r->descripcion;
        $dia_iva->vrunit = $r->vrunit;
        $dia_iva->vrtotal = $r->vrtotal;
        $dia_iva->mediopago = $r->mediopago;
        $dia_iva->numsoporte = $r->numsoporte;
        $dia_iva->obs = $r->obs;
        //$dia_iva->fechaentrega = $r->fechaentrega;
        $dia_iva->pvppublico = $r->pvppublico;
        $dia_iva->estado_id = $r->estado_id;

        $dia_iva->banco_estado_id = 3;
        $dia_iva->caja2_estado_id = 5;

        $file = $r->urlimagen;

        if($file != null && $file != ''){
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = date('YmdHis').'-'.$filename;

            /**$ruta=public_path('uploads/archivos/'.$picture);         

            Image::make($file->getRealPath())
                ->resize(800,null, function ($constraint){ 
                    $constraint->aspectRatio();
                })
                ->save($ruta,72);**/

            if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif'){
                $ruta=public_path('uploads/archivos/'.$picture);                    
                Image::make($file->getRealPath())
                    ->resize(1000,null, function ($constraint){ 
                        $constraint->aspectRatio();
                    })
                    ->save($ruta,72);
            }else{
                $file->move(public_path('uploads/archivos'), $picture); 
            }

            $dia_iva->urlimagen = $picture;
        }

        $dia_iva->date_new = $dateonly;
        $dia_iva->user_new = Auth::id();
        $dia_iva->user_update = Auth::id();
        $dia_iva->created_at = $datehour;
        $dia_iva->updated_at = $datehour;

        $dia_iva->save();

        return redirect()->route('dia_ivas.index')->with('mensaje', 'Registro ingresado con éxito!');

    }    
    public function update(Request $r, $id)
    {
        $dia_iva = Dia_iva::where('id',$id)->firstOrFail();

        /**$this->validate($r,[
            'nombre' => Rule::unique('dia_ivas', 'nombre')->ignore($dia_iva->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);**/

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        if($r->estado_id != null && ($dia_iva->estado_id != $r->estado_id)){
            $historial = new Historial;
            $historial->dia_iva_id =  $dia_iva->id;            
            $historial->estado_antes = $dia_iva->estado_id;
            $historial->estado_ahora = $r->estado_id;    
            $historial->tipo = "ESTADO CAJA1";    
            $historial->date_new = $dateonly;
            $historial->user_new = Auth::id();
            $historial->created_at = $datehour;
            $historial->save();
        }

        if($r->banco_estado_id != null && ($dia_iva->banco_estado_id != $r->banco_estado_id)){
            $historial = new Historial;
            $historial->dia_iva_id =  $dia_iva->id;            
            $historial->estado_antes = $dia_iva->banco_estado_id;
            $historial->estado_ahora = $r->banco_estado_id;    
            $historial->tipo = "ESTADO BANCOS";    
            $historial->date_new = $dateonly;
            $historial->user_new = Auth::id();
            $historial->created_at = $datehour;
            $historial->save();
        }

        if($r->caja2_estado_id != null && ($dia_iva->caja2_estado_id != $r->caja2_estado_id)){
            $historial = new Historial;
            $historial->dia_iva_id =  $dia_iva->id;            
            $historial->estado_antes = $dia_iva->caja2_estado_id;
            $historial->estado_ahora = $r->caja2_estado_id;    
            $historial->tipo = "ESTADO CAJA2";    
            $historial->date_new = $dateonly;
            $historial->user_new = Auth::id();
            $historial->created_at = $datehour;
            $historial->save();
        }

        if($dia_iva->banco_estado_id != 4){

            $dia_iva->tipoid = $r->tipoid;
            $dia_iva->identificacion = $r->identificacion;
            $dia_iva->nombre = $r->nombre;
            $dia_iva->tipofac = $r->tipofac;
            $dia_iva->tipodoc = $r->tipodoc;
            $dia_iva->numdoc = $r->numdoc;
            $dia_iva->fecha = $r->fecha;
            $dia_iva->categoria = $r->categoria;
            $dia_iva->genero = $r->genero;
            $dia_iva->cantidad = $r->cantidad;
            $dia_iva->unidad = $r->unidad;
            $dia_iva->descripcion = $r->descripcion;
            $dia_iva->vrunit = $r->vrunit;
            $dia_iva->vrtotal = $r->vrtotal;
            $dia_iva->mediopago = $r->mediopago;
            $dia_iva->numsoporte = $r->numsoporte;
            $dia_iva->obs = $r->obs;
            $dia_iva->pvppublico = $r->pvppublico;
        }

        if($dia_iva->banco_estado_id == 4){
            $dia_iva->fechaentrega = $r->fechaentrega;
        }

        if($r->estado_id != '' && $r->estado_id != null){
            $dia_iva->estado_id = $r->estado_id;
        }
        if($r->banco_estado_id != '' && $r->banco_estado_id != null){
            $dia_iva->banco_estado_id = $r->banco_estado_id;
        }
        if($r->caja2_estado_id != '' && $r->caja2_estado_id != null){
            $dia_iva->caja2_estado_id = $r->caja2_estado_id;
        }

        $file = $r->urlimagen;

        if($file != null && $file != ''){
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = date('YmdHis').'-'.$filename;

            /**$ruta=public_path('uploads/archivos/'.$picture);
            Image::make($file->getRealPath())
                ->resize(800,null, function ($constraint){ 
                    $constraint->aspectRatio();
                })
                ->save($ruta,72);**/
                
            if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif'){
                $ruta=public_path('uploads/archivos/'.$picture);                    
                Image::make($file->getRealPath())
                    ->resize(1000,null, function ($constraint){ 
                        $constraint->aspectRatio();
                    })
                    ->save($ruta,72);
            }else{
                $file->move(public_path('uploads/archivos'), $picture); 
            }


            $dia_iva->urlimagen = $picture;
        }

        
     
        $dia_iva->user_update = Auth::id();
        $dia_iva->updated_at = $datehour;
        
        $dia_iva->save();

        return redirect()->route('dia_ivas.index')->with('mensaje', "El Registro {$dia_iva->id} fue actualizado con éxito!");
        //return back()->with('mensaje','Registro actualizado con éxito!');
 
    }


    public function show($id)
    {
        $controlador = "dia_ivas";

        if(Auth::user()->validar_permiso('dia_show_nov')){
            $dia_iva = Dia_iva::where('id',$id)->firstOrFail();
            $medio_pagos = Medio_pago::orderBy('id','asc')->get();
            $historicos = Historial::where('dia_iva_id',$dia_iva->id)->orderBy('id','desc')->get();

            $titulo = 'Detalle';
            return view('dia_ivas.show',compact('historicos','medio_pagos','controlador','titulo','dia_iva'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function script_fechas()
    {
        $dia_ivas = Dia_iva::orderBy('id','asc')->get();

        foreach ($dia_ivas as $dia_iva) {
            $historial = Historial::where('dia_iva_id',$dia_iva->id)->where('estado_antes','5')->where('estado_ahora','6')->first();
            if($historial != null){
                if($dia_iva->fechaentrega == null){
                    $dia_iva->fechaentrega = $historial->date_new;
                    $dia_iva->save();
                }
            }
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('dia_delete_nov')){
            $dia_iva = Dia_iva::where('id',$id)->firstOrFail();
            try {
                $dia_iva->delete($id);
                return redirect()->route('dia_ivas.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('dia_ivas.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_dia_ivas(Request $r){
        $dia_ivas = Dia_iva::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('dia_ivas'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $dia_iva = new Dia_iva;
        $dia_iva->nombre = $r->nombre;
        $dia_iva->save();

        $dia_ivas = Dia_iva::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('dia_ivas'))->render();
        return response()->json(['options'=>$opciones]);
        
    }



    public function importar()
    {
        $controlador = "dia_ivas";

        if(Auth::user()->validar_permiso('dia_cargar')){

            $accion = url('dia_ivas/subir_archivo');
            $metodo = method_field('POST');
            $titulo = "Carga Archivo Configuración";
            $boton = "Cargar";
            return view('dia_ivas.importar',compact('accion','metodo','titulo','boton','usuario','controlador'));
            
        }else{
            return view('errors.access_denied');
        }
    }




    public function subir_archivo(Request $req){

        if(Auth::user()->validar_permiso('dia_cargar')){

            $this->validate($req,[
                'archivo' => 'required|mimes:xls,xlsx'
            ],[
                'archivo.required'=>"El campo archivo es requerido",
                'archivo.mimes'=>"El archivo debe ser de extensión .xlsx",
            ]);
        
            //$nombre_archivo = $req->file('archivo')->store('','local');
                
            date_default_timezone_set('America/Bogota');
            $fechahora=time();
            $dateonly=date("Y-m-d", $fechahora);
            $datehour= date("Y-m-d H:i:s", $fechahora);
            $bandera = true;
            $error = "";
            $mensaje = "";
            

            DB::beginTransaction();
            
            try{
                Excel::load($req->archivo, function($row) use ($dateonly,$datehour) {
                    $row->each(function($r) use ($dateonly,$datehour) {
                            try{


                                $validar_documento = Tipo_documento::where('codigo',$r->cod)
                                ->where('nombre',$r->detalle)->first();
                                
                                    if($validar_documento == null){

                                        $reg = new Tipo_documento;

                                        $reg->codigo = $r->cod;
                                        $reg->nombre = $r->detalle;
                                        $reg->codciu = $r->codciu;
                                        $reg->ciudad = $r->ciudad;
                                        $reg->coddpto = $r->coddpto;
                                        $reg->depto = $r->depto;

                                        $reg->date_new = $datehour;
                                        $reg->user_new = Auth::id();
                                        $reg->user_update = Auth::id();
                                        $reg->created_at = $datehour;
                                        $reg->updated_at = $datehour;

                                        $reg->save();
                                    }else{

                                        $reg = $validar_documento;
                                        $reg->codigo = $r->cod;
                                        $reg->nombre = $r->detalle;
                                        $reg->codciu = $r->codciu;
                                        $reg->ciudad = $r->ciudad;
                                        $reg->coddpto = $r->coddpto;
                                        $reg->depto = $r->depto;
                                        

                                        $reg->user_update = Auth::id();
                                        $reg->updated_at = $datehour;
                                        $reg->save();

                                    }

                            }catch(QueryException $e) {
                                $error = $e->getMessage();
                                $this->mensaje_errores = $this->mensaje_errores."<br><br>".$error;
                                $this->contador_errores = $this->contador_errores + 1;
                            }
                
                    });
                        
                });
            
            }catch(QueryException $e) {
                $bandera = false;
                $error = $e->getMessage();
                $mensaje = $mensaje."<br>".$error;
            }
            
            if($bandera == true && $this->contador_errores == 0){
                DB::commit();
                return redirect()->route('dia_ivas.importar')->with('mensaje', 'Archivo subido satisfactoriamente!'.$this->mensaje_errores);
            }else{
                DB::rollback();
                return redirect()->route('dia_ivas.importar')->with('alerta', 'No se pudo cargar el archivo. Verifique que no tenga caracteres extraños!'.$this->mensaje_errores);
            }
            

            
        }else{
            $title = "Error de acceso";
            return view('errors.access_denied');
        }
    }



}

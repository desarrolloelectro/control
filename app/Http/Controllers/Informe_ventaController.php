<?php

namespace App\Http\Controllers;

use App\Usuario;
use App\Informe_venta;
use App\Medio_pago;
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

class Informe_ventaController extends Controller
{

    protected $mensaje_errores = "";
    protected $contador_errores = 0;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $controlador = "informe_ventas";
        $subcon = 'informe_ventas';

        if(Auth::user()->validar_permiso('dia_access')){

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

            //********************* */
            
            $porciones = explode("-", $valor);

            if(isset($porciones[1])){
                $informe_ventas = Informe_venta::where('tipodoc',$porciones[0])->where('numdoc',$porciones[1])->paginate(50);

            }else{
                $informe_ventas = Informe_venta::where(function ($query) use ($valor){
                    $query->where('identificacion', 'LIKE', '%' . $valor . '%')
                    //->orWhere('nombre', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('tipofac', 'LIKE', '%' . $valor . '%')              
                    ->orWhere('tipodoc', 'LIKE', '%' . $valor . '%')              
                    ->orWhere('numdoc', 'LIKE', '%' . $valor . '%');              
                    //->orWhere('lugar', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('fecha', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('descripcion', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('mediopago', 'LIKE', '%' . $valor . '%')    ;          
                })->paginate(50);
            }
            

            

            $title = "Lista Informe Ventas";

            if($valor != ''){
                $informe_ventas->appends(['search' => $valor]);

            }
            $url_paginacion = route('informe_ventas.index');

            return view('informe_ventas.index', compact('valor','url_paginacion','title','informe_ventas','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function create()
    {
        $controlador = "informe_ventas";
        $subcon = 'informe_ventas';

        if(Auth::user()->validar_permiso('con_areas')){
            $accion = url('informe_ventas/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear Informe_venta";
            $boton = "Crear";

            $medio_pagos = Medio_pago::orderBy('id','asc')->get();
            return view('informe_ventas.create',compact('medio_pagos','accion','metodo','titulo','boton','controlador','subcon'));       
        }else{
            return view('errors.access_denied');
        }

    }
    public function edit($id)
    {
        $controlador = "informe_ventas";
        $subcon = 'informe_ventas';

        if(Auth::user()->validar_permiso('dia_edit')){
            $informe_venta = Informe_venta::where('id',$id)->firstOrFail();
            $medio_pagos = Medio_pago::orderBy('id','asc')->get();

            $accion = url("informe_ventas/actualizar/{$informe_venta->id}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar Informe_venta";
            $boton = "Actualizar";
            return view('informe_ventas.create',compact('medio_pagos','informe_venta','accion','metodo','titulo','boton','controlador','subcon'));        
        }else{
            return view('errors.access_denied');
        }

    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>['required','unique:informe_ventas,nombre'],
        ],[
            'nombre.required'=>"El campo Estado es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $informe_venta = new Informe_venta;

        $informe_venta->tipoid = $r->tipoid;
        $informe_venta->identificacion = $r->identificacion;
        $informe_venta->nombre = $r->nombre;
        $informe_venta->tipofac = $r->tipofac;
        $informe_venta->tipodoc = $r->tipodoc;
        $informe_venta->numdoc = $r->numdoc;
        $informe_venta->lugar = $r->lugar;
        $informe_venta->fecha = $r->fecha;
        $informe_venta->categoria = $r->categoria;
        $informe_venta->genero = $r->genero;
        $informe_venta->cantidad = $r->cantidad;
        $informe_venta->unidad = $r->unidad;
        $informe_venta->descripcion = $r->descripcion;
        $informe_venta->vrunit = $r->vrunit;
        $informe_venta->vrtotal = $r->vrtotal;
        $informe_venta->mediopago = $r->mediopago;
        $informe_venta->numsoporte = $r->numsoporte;
        $informe_venta->fechaentrega = $r->fechaentrega;
        $informe_venta->pvppublico = $r->pvppublico;

        $informe_venta->save();

        if(isset($r->opcion)){
            $opcion = $r->opcion;
            return redirect()->route('informe_ventas.index')->with('opcion', $opcion);
        }else{
            $opcion = ""; 
            return redirect()->route('informe_ventas.index')->with('mensaje', 'Registro ingresado con éxito!');
        }

    }    
    public function update(Request $r, $id)
    {
        $informe_venta = Informe_venta::where('id',$id)->firstOrFail();

        /**$this->validate($r,[
            'nombre' => Rule::unique('informe_ventas', 'nombre')->ignore($informe_venta->id,'id'),
        ],[
            'nombre.required'=>"El campo Estado Incapacidad es requerido",
            'nombre.unique'=>"El campo Estado Incapacidad ya se encuentra registrado",
        ]);**/

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
     
        /**$informe_venta->tipoid = $r->tipoid;
        $informe_venta->identificacion = $r->identificacion;
        $informe_venta->nombre = $r->nombre;
        $informe_venta->tipofac = $r->tipofac;
        $informe_venta->tipodoc = $r->tipodoc;
        $informe_venta->numdoc = $r->numdoc;
        $informe_venta->lugar = $r->lugar;
        $informe_venta->fecha = $r->fecha;
        $informe_venta->categoria = $r->categoria;
        $informe_venta->genero = $r->genero;
        $informe_venta->cantidad = $r->cantidad;
        $informe_venta->unidad = $r->unidad;
        $informe_venta->descripcion = $r->descripcion;
        $informe_venta->vrunit = $r->vrunit;
        $informe_venta->vrtotal = $r->vrtotal;**/
        $informe_venta->mediopago = $r->mediopago;
        $informe_venta->numsoporte = $r->numsoporte;
        $informe_venta->obs = $r->obs;
        //$informe_venta->fechaentrega = $r->fechaentrega;
        //$informe_venta->pvppublico = $r->pvppublico;

        $file = $r->urlimagen;

        if($file != null && $file != ''){
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = date('YmdHis').'-'.$filename;
            $ruta=public_path('uploads/archivos/'.$picture);

            //$file->move(public_path('uploads/archivos'), $picture);

            Image::make($file->getRealPath())
                ->resize(800,null, function ($constraint){ 
                    $constraint->aspectRatio();
                })
                ->save($ruta,72);

            $informe_venta->urlimagen = $picture;
        }

        /**if ($r->hasFile('urlimagen')){
            $urlfoto    = $r->file('urlimagen');
            $nombre     = 'nuevonombre.'.$urlfoto->guessExtension();
            $ruta=public_path('uploads/archivos'.$nombre);
            Image::make($urlfoto->getRealPath())
                ->resize(600,null, function ($constraint){ 
                    $constraint->aspectRatio();
                })
                ->save($ruta,72);
            
            $informe_venta->urlimagen = $nombre;
            
        }**/

        $informe_venta->user_update = Auth::id();
        $informe_venta->updated_at = $datehour;
        
        $informe_venta->save();

        //return redirect()->route('informe_ventas.index')->with('mensaje', 'Registro actualizado con éxito!');
        return back()->with('mensaje','Registro actualizado con éxito!');
 
    }


    public function show($id)
    {
        $controlador = "informe_ventas";

        if(Auth::user()->validar_permiso('dia_show')){
            $informe_venta = Informe_venta::where('id',$id)->firstOrFail();
            $medio_pagos = Medio_pago::orderBy('id','asc')->get();

            $titulo = 'Detalle';
            return view('informe_ventas.show',compact('medio_pagos','controlador','titulo','informe_venta'));        
        }else{
            return view('errors.access_denied');
        }

    }

    public function destroy($id)
    {
        if(Auth::user()->validar_permiso('dia_delete')){
            $informe_venta = Informe_venta::where('id',$id)->firstOrFail();
            try {
                $informe_venta->delete($id);
                return redirect()->route('informe_ventas.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('informe_ventas.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }

    public function actualizar_informe_ventas(Request $r){
        $informe_ventas = Informe_venta::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('informe_ventas'))->render();
    	return response()->json(['options'=>$opciones]);
    }

    public function registrar_ajax(Request $r){

        $informe_venta = new Informe_venta;
        $informe_venta->nombre = $r->nombre;
        $informe_venta->save();

        $informe_ventas = Informe_venta::orderBy('nombre','asc')->get();
        $opciones = view('cargar_select',compact('informe_ventas'))->render();
        return response()->json(['options'=>$opciones]);
        
    }



    public function importar()
    {
        $controlador = "informe_ventas";

        if(Auth::user()->validar_permiso('dia_cargar')){

            $accion = url('informe_ventas/subir_archivo');
            $metodo = method_field('POST');
            $titulo = "Carga Informe de Ventas";
            $boton = "Cargar";
            return view('informe_ventas.importar',compact('accion','metodo','titulo','boton','controlador'));
            
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


                                $validar_venta = Informe_venta::where('identificacion',$r->identificacion)
                                ->where('descripcion',$r->descripcion)->where('numdoc',$r->numdoc)->where('fecha',$r->fecha)->first();
                                
                                    if($validar_venta == null){

                                        $reg = new Informe_venta;

                                        $reg->tipoid = $r->tipoid;
                                        $reg->identificacion = $r->identificacion;
                                        $reg->nombre = $r->nombre;
                                        $reg->tipofac = $r->tipofac;
                                        $reg->tipodoc = $r->tipodoc;
                                        $reg->numdoc = $r->numdoc;
                                        $reg->lugar = $r->lugar;
                                        $reg->fecha = $r->fecha;
                                        $reg->categoria = $r->categoria;
                                        $reg->genero = $r->genero;
                                        $reg->cantidad = $r->cantidad;
                                        $reg->unidad = $r->unidad;
                                        $reg->descripcion = $r->descripcion;
                                        $reg->vrunit = $r->vrunit;
                                        $reg->vrtotal = $r->vrtotal;
                                        //$reg->mediopago = $r->mediopago;
                                        //$reg->numsoporte = $r->numsoporte;
                                        $reg->fechaentrega = $r->fechaentrega;
                                        $reg->pvppublico = $r->pvppublico;

                                        $reg->date_new = $datehour;
                                        $reg->user_new = Auth::id();
                                        $reg->user_update = Auth::id();
                                        $reg->created_at = $datehour;
                                        $reg->updated_at = $datehour;

                                        $reg->save();
                                    }else{

                                        //dd($r->identificacion);

                                        $reg = $validar_venta;

                                        $reg->tipoid = $r->tipoid;
                                        $reg->identificacion = $r->identificacion;
                                        $reg->nombre = $r->nombre;
                                        $reg->tipofac = $r->tipofac;
                                        $reg->tipodoc = $r->tipodoc;
                                        $reg->numdoc = $r->numdoc;
                                        $reg->lugar = $r->lugar;
                                        $reg->fecha = $r->fecha;
                                        $reg->categoria = $r->categoria;
                                        $reg->genero = $r->genero;
                                        $reg->cantidad = $r->cantidad;
                                        $reg->unidad = $r->unidad;
                                        $reg->descripcion = $r->descripcion;
                                        $reg->vrunit = $r->vrunit;
                                        $reg->vrtotal = $r->vrtotal;
                                        //$reg->mediopago = $r->mediopago;
                                        //$reg->numsoporte = $r->numsoporte;
                                        $reg->fechaentrega = $r->fechaentrega;
                                        $reg->pvppublico = $r->pvppublico;

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
                return redirect()->route('informe_ventas.importar')->with('mensaje', 'Archivo subido satisfactoriamente!'.$this->mensaje_errores);
            }else{
                DB::rollback();
                return redirect()->route('informe_ventas.importar')->with('alerta', 'No se pudo cargar el archivo. Verifique que no tenga caracteres extraños!'.$this->mensaje_errores);
            }
            

            
        }else{
            $title = "Error de acceso";
            return view('errors.access_denied');
        }
    }



}

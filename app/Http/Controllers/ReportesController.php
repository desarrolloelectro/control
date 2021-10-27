<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cotizacion;
use App\Cotizacion_detalle;
use App\Gasto;
use App\Gasto_Detalle;
use App\Agencia;
use App\Tipo_gasto;
use App\Usuario_agencia;
use App\Informe_venta;
use App\Dia_iva;
use App\Usuario;

use App\Exports\Informe_ventasExport;
use App\Exports\Dia_ivasExport;
use App\Exports\CotizacionesExport;
use App\Exports\UsuariosExport;

use App\Dsi;
use App\DsiData;
use App\Exports\DsiExport;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;

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

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
    }
    
    public function index()
    {
        $nivel = Auth::user()->nivel_form;         

        if(Auth::user()->validar_permiso('rep_acceder')){        
            $title = "Reportes";
            $controlador = "reportes";
            return view('reportes.index', compact('title','controlador'));
        }else{
            return view('errors.access_denied');
        }
    }

    public function cotizaciones()
    {
        if(Auth::user()->validar_permiso('rep_acceder')){
            
            $accion = url('reportes/cotizaciones_export');
            $metodo = method_field('POST');
            $title = "Reporte Autorizaciones / Gastos";
            $boton = "Generar reporte";
            $regresar = $regresar = route('reportes.index');
            $controlador = "reportes";

            date_default_timezone_set('America/Bogota');
            $fechahora=time();
            $dateonly=date("Y-m-d", $fechahora);
            $datehour= date("Y-m-d H:i:s", $fechahora);
            $fecha1 = $dateonly;

            $agencias = Agencia::orderBy('agennom','asc')->where('agennom','!=','.')->get();
            $tipo_gastos = Tipo_gasto::orderBy('nombre','asc')->get();

            $usuario_agencia = Usuario_agencia::where('usuario_id',Auth::id())->first();
            $lista_agencias = array();
            if($usuario_agencia != null){
                $lista_agencias = explode(",",$usuario_agencia->agencias);
            }
            array_push($lista_agencias,Auth::user()->agencia);

            return view('reportes.cotizaciones',compact('lista_agencias','fecha1','agencias','tipo_gastos','controlador','accion','metodo','title','boton','regresar'));
            
        }else{
            return view('errors.access_denied');
        }
    }

    public function cotizaciones_export (Request $r){

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $query = Cotizacion::query();

        $agencia_id = $r->agencia_id;
        $tipo_gasto_id = $r->tipo_gasto_id;
        $fechainicial = $r->fecha1;
        $fechafinal = $r->fecha2;

        if($agencia_id != "0"){
            $query = $query->where('agencia_id',$agencia_id);
        }else{
            if(Auth::user()->validar_permiso('rep_cot_usu')){
                $query = $query->where('user_new',Auth::id());
            }
        }
        if($tipo_gasto_id != "0"){
            $query = $query->where('tipo_gasto_id',$tipo_gasto_id);
        }
        
        if($fechainicial == ""){$fechainicial = "0001-01-01";}
        if($fechafinal == ""){$fechafinal = "9999-01-01";}

        $query = $query->whereBetween('date_new', [$fechainicial, $fechafinal]);
        $consulta = $query->orderBy('id','asc')->get();

        if(count($consulta)== 0){
            return redirect()->route('reportes.cotizaciones')->with('alerta', 'No se encontraron registros!');
        }

        return Excel::download(new CotizacionesExport($consulta),"REPORTE_COTIZACIONES_".$dateonly.".xlsx");
    }


    public function usuarios()
    {
        if(Auth::user()->validar_permiso('rep_usuarios_com')){
            
            $accion = url('reportes/usuarios_export');
            $metodo = method_field('POST');
            $title = "Reporte Usuarios";
            $boton = "Generar Reporte";
            $regresar = route('reportes.index');
            $controlador = "reportes";

            date_default_timezone_set('America/Bogota');
            $fechahora=time();
            $dateonly=date("Y-m-d", $fechahora);
            $datehour= date("Y-m-d H:i:s", $fechahora);
            //$fecha1 = $dateonly;
            $fecha1 = '';
            $fecha2 = $dateonly;

            return view('reportes.usuarios',compact('fecha1','fecha2','controlador','accion','metodo','title','boton','regresar'));
            
        }else{
            return view('errors.access_denied');
        }
    }

    public function usuarios_export (Request $r){

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $query = Usuario::query();

        $fechainicial = $r->fecha1;
        $fechafinal = $r->fecha2;
        
        if($fechainicial == ""){$fechainicial = "0000-00-00";}
        if($fechafinal == ""){$fechafinal = "9999-01-01";}

        $query = $query->whereBetween('user_fec_new', [$fechainicial, $fechafinal]);
        $consulta = $query->orderBy('user_fec_new','desc')->get();

        if(count($consulta)== 0){
            return redirect()->route('reportes.usuarios')->with('alerta', 'No se encontraron registros!');
        }

        return Excel::download(new UsuariosExport($consulta),"REPORTE_USUARIOS_".$dateonly.".xlsx");
    }

    public function informe_ventas_export (Request $r){

        if(Auth::user()->validar_permiso('rep_acceder')){

            date_default_timezone_set('America/Bogota');
            $fechahora=time();
            $dateonly=date("Y-m-d", $fechahora);
            $datehour= date("Y-m-d H:i:s", $fechahora);
            
            $query = Informe_venta::query();
            $consulta = $query->orderBy('id','asc')->get();
            
            if(count($consulta)== 0){
                return redirect()->route('informe_ventas.index')->with('alerta', 'No se encontraron registros!');
            }

            return Excel::download(new Informe_ventasExport($consulta),"REPORTE_DIA_SIN_IVA_JULIO_2020_".$dateonly.".xlsx");
                       

        }else{
            return view('errors.access_denied');
        }
    }




    public function dsi_export (Request $request){

        //if(Auth::user()->validar_permiso($request->permission)){
            date_default_timezone_set('America/Bogota');
            $fechahora=time();
            $dateonly=date("Y-m-d", $fechahora);
            $datehour= date("Y-m-d H:i:s", $fechahora);
            $data = \App\DsiData::where('dsi_id',$request->id)->whereRaw('deleted_by IS NULL')->orderBy('id','asc')->get();
            if(count($data)== 0){
                return redirect()->route('dsi.index')->with('alerta', 'No se encontraron registros!');
            }
            $data = new DsiExport($data, $request->id);
            //print_r($data->collection());
            //exit();
            return Excel::download($data,$request->filename."_".$dateonly.".xlsx");
        //}else{
         //   return view('errors.access_denied');
        //}
    }

    public function dia_ivas_export (Request $r){
        if(Auth::user()->validar_permiso('dia_rep_nov')){
            date_default_timezone_set('America/Bogota');
            $fechahora=time();
            $dateonly=date("Y-m-d", $fechahora);
            $datehour= date("Y-m-d H:i:s", $fechahora);
            $query = Dia_iva::query();
            $consulta = $query->orderBy('id','asc')->get();
            if(count($consulta)== 0){
                return redirect()->route('dia_ivas.index')->with('alerta', 'No se encontraron registros!');
            }
            return Excel::download(new Dia_ivasExport($consulta),"REPORTE_DIA_SIN_IVA_NOVIEMBRE_2020_".$dateonly.".xlsx");
        }else{
            return view('errors.access_denied');
        }
    }

    
    
}
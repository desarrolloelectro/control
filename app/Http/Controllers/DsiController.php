<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dsi;
use App\Permiso;
use App\DsiAudit;
use App\DsiMeta;
use App\DsiPermission;

use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class DsiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use SoftDeletes;
    private $dev = false;//variable temporal para desarrollo

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.data');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            //$dsi = new Dsi();
            $valor = "";
            if(isset($_GET['search'])){
                $valor = $_GET['search'];
                session(['valor_session' => $valor]);
            }
            $valorname = "";
            if(isset($_GET['name'])){
                $valorname = $_GET['name'];
                session(['valorname_session' => $valorname]);
            }
            $valorname = session('valorname_session');
            $fecha = "";
            if(isset($_GET['date'])){
                $fecha = $_GET['date'];
                session(['valor_session_dsi_date' => $fecha]);
            }
            $fecha = session('valor_session_dsi_date');
          //dd($fecha);
         
            $dsi = Dsi::select('dsi.*')
                ->where(function ($query) use ($valor, $fecha, $valorname){
                    if($fecha!=""){
                        $query->Where('date', '=', $fecha);
                    }
                    if($valorname!=""){
                        $query->where('name', 'LIKE', '%' . $valorname . '%'); 
                    }
                    if($valor!=""){
                        $query->where('created_by', '=', $valor)              
                            ->orWhere('created_by', '=', $valor); 
                    }
                    
            });      
     
            
                //->orWhere('name', 'LIKE', '%' . $valor . '%')     
                  
           
            $dsi = $dsi->paginate();

            if($valor != '' && $valor != null){
                $dsi->appends(['search' => $valor]);
            }
            $title = '<i class="icon fa fa-shopping-bag"></i> Días sin IVA';
            $controlador = "dsi";
            $subcon = 'dsi';

            $url_paginacion = route('dsi.index');
            return view('dsi.index', compact('dsi', 'title', 'valor', 'fecha', 'valorname', 'url_paginacion', 'controlador', 'subcon'));        
        }else{
            return view('errors.access_denied');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.create');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $accion = route('dsi.store');
            $metodo = method_field('POST');
            $titulo = "Crear Día sin IVA";
            $boton = "Crear";
            $controlador = "dsi";
            $subcon = 'dsi';
            $editar_datos = true;
            $permisos = Permiso::orderBy('id','asc')->get();
            $dsi = new Dsi();
            return view('dsi.create',compact('dsi','accion','metodo','titulo','boton','controlador','subcon','editar_datos', 'permisos'));       

        }else{
            return view('errors.access_denied');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.create');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $dsi = new Dsi();
            $dsi->last_id = 0;
            $dsi->name = $request->name;
            $dsi->date = $request->date;
            $dsi->state = $request->state;
            $dsi->permission = $request->permission;
            $dsi->created_at = date("Y-m-d H:i:s");
            $dsi->created_by = Auth::user()->coduser;
            $result = $dsi->save();
            if($result){
                return redirect()->route('dsi.index')->with('mensaje', 'Registro ingresado con éxito!');
            }else{
                return redirect()->route('dsi.index')->with('alerta', 'No se pudo ingresar el registro!');
            }
        }else{
            return view('errors.access_denied');
        }
    

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.edit');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $dsi = Dsi::where('id',$id)->firstOrFail();
            $accion = route('dsi.update_fields',['id' => $dsi->id]);
            $metodo = method_field('POST');
            $titulo = $dsi->name;
            $titulo2 = "Detalles";
            $boton = "Modificar";
            $controlador = "dsi";
            $subcon = 'dsi';
            $editar_datos = false;
            $dsi->metas();
            $types = DsiMeta::$types;
            $fields_data = Dsi::$fields_data;
            $enable_meta = Dsi::$meta;
            $permisos = Permiso::orderBy('id','asc')->get();
            return view('dsi.show',compact('dsi', 'enable_meta', 'accion','types', 'fields_data', 'metodo','titulo','titulo2','boton','controlador','subcon','editar_datos', 'permisos'));       

        }else{
            return view('errors.access_denied');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.edit');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $dsi = Dsi::where('id',$id)->firstOrFail();
            $accion = route('dsi.update',['id' => $dsi->id]);
            $metodo = method_field('POST');
            $titulo = "Crear Día sin IVA";
            $boton = "Modificar";
            $controlador = "dsi";
            $subcon = 'dsi';
            $editar_datos = true;
            $permisos = Permiso::orderBy('id','asc')->get();
            return view('dsi.create',compact('dsi','accion','metodo','titulo','boton','controlador','subcon','editar_datos', 'permisos'));       

        }else{
            return view('errors.access_denied');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.edit');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $dsi = Dsi::where('id',$id)->firstOrFail();
            $dsi->name = $request->name;
            $dsi->date = $request->date;
            $dsi->state = $request->state;
            $dsi->permission = $request->permission;
            $dsi->updated_at = date("Y-m-d H:i:s");
            $dsi->updated_by = Auth::user()->coduser;
            $result = $dsi->save();
            if($result){
                return redirect()->route('dsi.index')->with('mensaje', 'Registro actualizado con éxito!');
            }else{
                return redirect()->route('dsi.index')->with('alerta', 'No se pudo actualizar el registro!');
            }
        }else{
            return view('errors.access_denied');
        }
    }
    public function update_fields(Request $request, $id)
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.edit');
        $permiso2 = DsiPermission::dsi_permiso(0,'dsi.meta.edit');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $dsi = Dsi::where('id',$id)->firstOrFail();
            if(isset($request->fields) && !empty($request->fields)) $dsi->fields = json_encode($request->fields);
            else $dsi->fields = "[]";
            if(isset($request->fields_report) && !empty($request->fields_report)) $dsi->fields_report = json_encode($request->fields_report);
            else $dsi->fields_report = "[]";
            if(isset($request->meta_fields) && !empty($request->meta_fields)) $dsi->meta_fields = json_encode($request->meta_fields);
            else $dsi->meta_fields = "[]";
            if(isset($request->meta_fields_report) && !empty($request->meta_fields_report)) $dsi->meta_fields_report = json_encode($request->meta_fields_report);
            else $dsi->meta_fields_report = "[]";
            if(isset($request->fields_view) && !empty($request->fields_view)) $dsi->fields_view = json_encode($request->fields_view);
            else $dsi->fields_view = "[]";
            if(isset($request->meta_fields_view) && !empty($request->meta_fields_view)) $dsi->meta_fields_view = json_encode($request->meta_fields_view);
            else $dsi->meta_fields_view = "[]";
            $result = $dsi->save();
            if($result){
                return redirect()->route('dsi.show',['id' => $id])->with('mensaje', 'Registro actualizado con éxito!');
            }else{
                return redirect()->route('dsi.show',['id' => $id])->with('alerta', 'No se pudo actualizar el registro!');
            }
        }else{
            return view('errors.access_denied');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function  destroy($id)
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.archive');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $dsi = Dsi::where('id',$id)->firstOrFail();
            try {
                $dsi->deleted_at = date("Y-m-d H:i:s");
                $dsi->deleted_by = Auth::user()->coduser;
                $result = $dsi->save();
                if ($result){
                    $dsi_audit = new DsiAudit();
                    $dsi_audit->audit = 'Registro Eliminado';
                    $dsi_audit->user = Auth::user()->coduser;
                    $dsi_audit->dsi_group = 'dsi';
                    $dsi_audit->dsi_id = $id;
                    $dsi_audit->save();
                    return redirect()->route('dsi.index')->with('mensaje', 'Registro eliminado con éxito!');
                }else{
                    return redirect()->route('dsi.index')->with('alerta', 'Error, el registro no se pudo eliminar');
                }
            } 
            catch(QueryException $e) {
                return redirect()->route('dsi.index')->with('alerta', 'No se pudo eliminar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }        

    }
    
    public function restore($id)
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.restore');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $dsi = Dsi::where('id',$id)->firstOrFail();
            try {
                $dsi->deleted_at = NULL;
                $dsi->deleted_by = NULL;
                $result = $dsi->save();
                if ($result){
                    $dsi_audit = new DsiAudit();
                    $dsi_audit->audit = 'Registro Restaurado';
                    $dsi_audit->user = Auth::user()->coduser;
                    $dsi_audit->dsi_group = 'dsi';
                    $dsi_audit->dsi_id = $id;
                    $dsi_audit->save();
                    return redirect()->route('dsi.index')->with('mensaje', 'Registro restaurado con éxito!');
                }else{
                    return redirect()->route('dsi.index')->with('alerta', 'Error, el registro no pudo ser restaurado');
                }
            } 
            catch(QueryException $e) {
                return redirect()->route('dsi.index')->with('alerta', 'No se pudo restaurar el registro!');
            }          
        }else{
            return view('errors.access_denied');
        }
    }
    public function history($id)
    {
        $permiso = DsiPermission::dsi_permiso(0,'dsi.history');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $dsi = Dsi::where('id',$id)->firstOrFail();
            $valor = "";
                if(isset($_GET['search'])){
                    $valor = $_GET['search'];
                    session(['valor_session_h' => $valor]);
                }
                $valor = session('valor_session_h');

            $histories = DsiAudit::where('context_id', '=', $id)
                ->where('context', '=', 'dsi')    
                ->where(function ($query) use ($valor, $id){
                $query->where('audit', 'LIKE', '%' . $valor . '%')          
                ->orWhere('user', 'LIKE', '%' . $valor . '%')          
                ->orWhere('date', 'LIKE', '%' . $valor . '%');          
            })->paginate();
            
            $title = "Historial de cambios Día sin IVA ".$dsi->name;
            $url_paginacion = route('dsi.history', ['id' => $id]);
            return view('dsi.history',compact('dsi','histories','title','valor', 'url_paginacion'));   
        }else{
            return view('errors.access_denied');
        }    
    }
}

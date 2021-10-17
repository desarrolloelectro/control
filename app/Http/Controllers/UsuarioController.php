<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

use Illuminate\Http\Request;
use App\Usuario;
use App\Rol;
use App\Agencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class UsuarioController extends Controller
{
    protected $mensaje_errores = "";
    protected $contador_errores = 0;
    protected $contador_filas = 0;
    protected $contador_encontrados = 0;
    protected $cadena = "";
    protected $contador_creados = 0;
    protected $cadena_creados = "";

    public function __construct()
    {
        $this->middleware('auth');
        
    }

    public function index()
    {
        $controlador = "configuracion";

        if(Auth::user()->validar_permiso('con_usuarios')){

            $valor = "";
            if(isset($_GET['search'])){
                $valor = $_GET['search'];
            }

            $usuarios = Usuario::where(function ($query) use ($valor){
                $query->where('coduser', 'LIKE', '%' . $valor . '%')
                ->orWhere('cedula', 'LIKE', '%' . $valor . '%')              
                ->orWhere('nombre', 'LIKE', '%' . $valor . '%');                
            })->paginate(50);

            $titulo = "LISTA DE USUARIOS";

            if($valor != ''){
                $usuarios->appends(['search' => $valor]);

            }
            $url_paginacion = route('usuarios.index');

            return view('usuarios.index', compact('valor','url_paginacion','usuarios','titulo','controlador'));
        }else{
            return view('errors.access_denied');
        }
    }

    public function create()
    {
        $controlador = "configuracion";

        if(Auth::user()->validar_permiso('con_usuarios')){

            $agencias = Agencia::where('activo','1')->orderBy('agennom','asc')->get();
            $roles = Rol::orderBy('nombre','asc')->get();
            $accion = url('usuarios/guardar');
            $metodo = method_field('POST');
            $titulo = "Crear usuario";
            $boton = "Crear usuario";
            return view('usuarios.create',compact('roles','accion','metodo','titulo','boton','agencias','controlador'));
            
        }else{
            return view('errors.access_denied');
        }
    }
    public function edit($id)
    {
        $controlador = "configuracion";

        if(Auth::user()->validar_permiso('con_usuarios')){

            $agencias = Agencia::where('activo','1')->orderBy('agennom','asc')->get();
            $roles = Rol::orderBy('nombre','asc')->get();

            $user = Usuario::findOrFail($id);
            $accion = url("usuarios/actualizar/{$user->coduser}");
            $metodo = method_field('PUT');
            $titulo = "Actualizar usuario";
            $boton = "Actualizar";
            return view('usuarios.create',compact('roles','accion','metodo','titulo','boton','user','agencias','controlador'));

        }else{
            return view('errors.access_denied');
        }
    }
    public function store(Request $r)
    {

        $this->validate($r,[
            'coduser'=>['required','unique:toolset_perf.usuarios,coduser'],
            'cedula'=>'',
            'nombre'=>'',
            'correo'=>'',
            'telefono'=>'',
            'contrasena'=>'',
            'nivel_control'=>'',
            'useractivo'=>'',
        ],[
            'coduser.required'=>"El campo Usuario es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
        
        try{
            $user = new Usuario;
            $user->coduser = $r->coduser;
            $user->cedula = $r->cedula;
            $user->nombre = $r->nombre;
            $user->correo = $r->correo;
            $user->telefono = $r->telefono;
            $user->contrasena = md5($r->contrasena);
            $user->nivel = '0';
            $user->nivel_cotiza = '0';
            $user->nivel_inve = '0';
            $user->nivel_help = '0';
            $user->nivel_control = $r->nivel_control;
            $user->cargo = '1';
            $user->descripcion = '0';
            $user->agencia = $r->agencia;
            $user->useractivo = "1";
            $user->cantidad = "0";
            $user->user_fec_new = $dateonly;
            $user->user_new = Auth::id();
            $user->user_update = Auth::id();
            $user->user_created_at = $datehour;
            $user->user_updated_at = $datehour;
            $user->dependencia = '1';
            $user->direccionip = '';
            
            $user->save();

            return redirect()->route('usuarios.index')->with('mensaje', 'Registro ingresado con éxito!');

        }catch(QueryException $e) {
            return redirect()->route('usuarios.create')->with('alerta', 'No se pudo crear el usuario. Verifique la cantidad de caracteres en el campo Usuario!');
        } 


    }      
    public function update(Request $r, $id)
    {
       
        $user = Usuario::findOrfail($id);
        $this->validate($r,[
            'coduser'=>'required',
            'cedula'=>'',
            'nombre'=>'',
            'correo'=>'',
            'telefono'=>'',
            'contrasena'=>'',
            'nivel_control'=>'',
            'useractivo'=>'',
        ],[
            'coduser.required'=>"El campo Usuario es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
        
        
        $user->coduser = $user->coduser;
        $user->cedula = $r->cedula;
        $user->nombre = $r->nombre;
        $user->correo = $r->correo;
        $user->telefono = $r->telefono;
        if($r->contrasena != ""){
            $user->contrasena = md5($r->contrasena);
        }else{
            $user->contrasena = $user->contrasena;
        }
        $user->nivel_control = $r->nivel_control;
        $user->agencia = $r->agencia;
        $user->user_update = Auth::id();
        $user->user_updated_at = $datehour;
        $user->save();

        
        return redirect("usuarios/{$user->coduser}/editar")->with('mensaje', 'Actualización realizada con éxito!');
        //return redirect("/usuarios/show/{$usuario->id}");
        //return redirect()->route('usuarios.show',['usuario'=>$usuario]);
    }


    public function show($id)
    {

        if(Auth::user()->validar_permiso('con_usuarios')){

            $user = Usuario::findOrFail($id);
            $titulo = 'Detalle de usuario';

            return view('usuarios.show',compact('titulo','user'));

        }else{
            return view('errors.access_denied');
        }
    }

    public function destroy($id)
    {

        if(Auth::user()->validar_permiso('con_usuarios')){
            $usuario = Usuario::findOrFail($id);
            try {
                $usuario->delete($id);
                return redirect()->route('usuarios.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('usuarios.index')->with('alerta', 'No se pudo eliminar el registro!');
            } 
        }else{
            return view('errors.access_denied');
        }
    }  

    public function perfil()
    {
        $user = Usuario::findOrFail(Auth::id());
        $accion = url("usuarios/actualizar_perfil/{$user->coduser}");
        $metodo = method_field('PUT');
        $titulo = "Mi perfil";
        $boton = "Actualizar";
        $perfil = true;
        $controlador = "configuracion";

        return view('usuarios.create',compact('controlador','user','accion','metodo','titulo','boton','perfil'));
    }

    
    public function actualizar_perfil(Request $r, $id)
    {
        $vin = Usuario::findOrfail($id);

        $this->validate($r,[
            'cedula' =>'required',
            'coduser'=>Rule::unique('toolset_perf.usuarios', 'coduser')->ignore($vin->coduser,'coduser'),
            'correo' => 'required',
            'nombre' => 'required',
        ],[
            'cedula.required'=>"El campo cédula es requerido",
            'cedula.unique'=>"El campo cédula ya existe",
            'nombre.required'=>"El campo nombre es requerido",
            'coduser.unique'=>"El usuario ingresado ya está en uso",
            'correo.unique'=>"El correo electrónico debe estar disponible",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
        
        //$vin->cedula = $r->cedula;
        $vin->nombre = $r->nombre;
        $vin->correo = $r->correo;
        $vin->telefono = $r->telefono;
        if($r->contrasena != ""){
            $vin->contrasena = md5($r->contrasena);
        }
        $vin->user_updated_at = $datehour;
        $vin->save();


        return back()->with('mensaje', 'Actualización realizada con éxito!');
    }

    public function registrar_ajax(Request $r){

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $validar_usuario = Usuario::where('coduser',$r->coduser)->first();
        $validar_cedula = Usuario::where('cedula',$r->cedula)->first();

        if ($validar_usuario == null && $validar_cedula == null){
            $usuario = new Usuario;
            $usuario->nombre = $r->nombre;
            $usuario->agencia = "0";
            $usuario->cod_vendedor = $r->cod_vendedor;
            $usuario->descripcion = "";
            $usuario->coduser = $r->coduser;
            $usuario->cedula = $r->cedula;
            $usuario->correo = "";
            $usuario->direccionip = "";
            $usuario->user_fec_new = $dateonly;
            $usuario->user_new = Auth::id();
            $usuario->user_update = Auth::id();
            $usuario->user_created_at = $datehour;
            $usuario->user_updated_at = $datehour;
            $usuario->direccionip = "";
            
            $usuario->nivel = '0' ;
            $usuario->nivel_cotiza = '0' ;
            $usuario->nivel_help = '0' ;
            $usuario->nivel_inve = '0' ;
            $usuario->nivel_control = '20' ;
            $usuario->nivel_jurid = '0' ;
            $usuario->nivel_repues = '0' ;
            $usuario->nivel_cartera = '0' ;
            $usuario->useractivo = '1' ;
            $usuario->contrasena = md5('1234');
            $usuario->save();


            $vendedores = Usuario::orderBy('nombre','asc')->where('nivel_control','20')->get();
            $opciones = view('cargar_select',compact('vendedores'))->render();
            $cadena = "<option value='$usuario->coduser'>$usuario->nombre</option>";
            return response()->json(['options'=>$cadena]);
            
        }else{
            return response()->json(['status'=>'error','options'=>"El usuario ya se encuentra registrado, ingrese un usuario diferente"]);
        }

        
        
    }

    
    public function importar()
    {
        $controlador = "reportes";

        if(Auth::user()->validar_permiso('con_usuarios')){

            $agencias = Agencia::all();
            $accion = url('usuarios/subir_archivo');
            $metodo = method_field('POST');
            $titulo = "Carga masiva de usuarios";
            $boton = "Validar";
            return view('usuarios.importar',compact('accion','metodo','titulo','boton','controlador','agencias','cargas'));
            
        }else{
            return view('errors.access_denied');
        }
    }


    public function subir_archivo(Request $req){


        if(Auth::user()->nivel_control == '1'){
            $this->validate($req,[
                'archivo' => 'required|mimes:xls,xlsx'
            ],[
                'archivo.required'=>"El campo archivo es requerido",
                'archivo.mimes'=>"El archivo debe ser de extensión .xls o .xlsx",
            ]);
            
            date_default_timezone_set('America/Bogota');
            $fechahora=time();
            $dateonly=date("Y-m-d", $fechahora);
            $datehour= date("Y-m-d H:i:s", $fechahora);

            $bandera = true;
            $error = "";
            $regional = "0";
            $mensaje = "";
            
            DB::beginTransaction();
            
            try{
            Excel::load($req->archivo, function($reader) use ($dateonly,$datehour) {
                $reader->each(function($row) use ($dateonly,$datehour) {
                    try{
                    $identificacion = $row->identificacion;
                    $usuario = Usuario::where('cedula',$identificacion)->first();

                    if($usuario != null){
                        
                        $usuario->nivel_control = '3';
                        $usuario->agencia = $row->agencia;
                        $usuario->correo = $row->correo;
                        $usuario->telefono = $row->telefono;
                        $usuario->nombre = $row->nombre;
                        $usuario->save();

                        $this->contador_encontrados = $this->contador_encontrados + 1 ;
                        $this->cadena = $this->cadena . $usuario->nombre. " - ".$usuario->cedula. " - ".$usuario->coduser. "<br>";

                    }else{

                        
                        $user = new Usuario;
                        $user->coduser = $row->usuario;
                        $user->cedula = $row->identificacion;
                        $user->nombre = $row->nombre;
                        $user->correo = $row->correo;
                        $user->telefono = $row->telefono;
                        $user->contrasena = md5('1234');
                        $user->nivel = '0';
                        $user->nivel_cotiza = '0';
                        $user->nivel_inve = '0';
                        $user->nivel_help = '0';
                        $user->nivel_control = '3';
                        $user->cargo = '1';
                        $user->descripcion = '0';
                        $user->agencia = $row->agencia;
                        $user->useractivo = "1";
                        $user->cantidad = "0";
                        $user->user_fec_new = $dateonly;
                        $user->user_new = Auth::id();
                        $user->user_update = Auth::id();
                        $user->user_created_at = $datehour;
                        $user->user_updated_at = $datehour;
                        $user->dependencia = '1';
                        $user->direccionip = '';
                        
                        $user->save();

                        $this->contador_creados = $this->contador_creados + 1 ;
                        $this->cadena_creados = $this->cadena_creados . $user->nombre. " - ".$user->cedula. " - ".$user->coduser. "<br>";
                    }

                    $this->contador_filas = $this->contador_filas + 1 ;
                    
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
                return redirect()->route('usuarios.importar')->with('mensaje', 'Archivo subido satisfactoriamente!<br>'
                ."Usuarios Analizados: ".$this->contador_filas."<br>"
                ."Usuarios Encontrados y actualizados: ".$this->contador_encontrados."<br>"
                ."<br><strong>Lista de Usuarios encontrados y actualizados: </strong><br><br>".$this->cadena."<br>"
                ."Usuarios Creados: ".$this->contador_creados."<br>"
                ."<br><strong>Lista de Usuarios creados: </strong><br><br>".$this->cadena_creados."<br>");
            }else{
                DB::rollback();
                return redirect()->route('usuarios.importar')->with('alerta', 'No se pudo cargar el archivo. Verifique que no tenga caracteres extraños!'.$this->mensaje_errores);
            }
            

            
        }else{
            $title = "Error de acceso";
            return view('errors.access_denied');
        }
    }




    //**************************  INACTIVAR USUARIOS PARA TOOLSET PERFILACIONES  *************************** */

    public function inactivar_usuarios(Request $req){


        if(Auth::user()->nivel == '1'){
            $this->validate($req,[
                'archivo' => 'required|mimes:xls,xlsx'
            ],[
                'archivo.required'=>"El campo archivo es requerido",
                'archivo.mimes'=>"El archivo debe ser de extensión .xls o .xlsx",
            ]);
    
            //$nombre_archivo = $req->file('archivo')->store('','local');
            
    
            date_default_timezone_set('America/Bogota');
            $fechahora=time();
            $dateonly=date("Y-m-d", $fechahora);
            $datehour= date("Y-m-d H:i:s", $fechahora);

            $bandera = true;
            $error = "";
            $regional = "0";
            $mensaje = "";
            
            DB::beginTransaction();
            
            try{
            Excel::load($req->archivo, function($reader) use ($dateonly,$datehour) {
                $reader->each(function($row) use ($dateonly,$datehour) {
                    try{
                    $identificacion = $row->identificacion;
                    $usuario = Usuario::where('cedula',$identificacion)->first();
                    if($usuario != null){
                        
                        $usuario->contrasena = "inactivo";
                        $usuario->nivel = '0';
                        $usuario->nivel_cotiza = '0';
                        $usuario->nivel_inve = '0';
                        $usuario->nivel_help = '0';
                        $usuario->nivel_control = '0';
                        $usuario->nivel_kardocx = '0';
                        $usuario->useractivo = '0';
                        $usuario->save();

                        $this->contador_encontrados = $this->contador_encontrados + 1 ;
                        $this->cadena = $this->cadena . $usuario->nombre. " - ".$usuario->cedula. " - ".$usuario->coduser. "<br>";

                    }

                    $this->contador_filas = $this->contador_filas + 1 ;
                    
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
                return redirect()->route('usuarios.importar')->with('mensaje', 'Archivo subido satisfactoriamente!<br>'
                ."Usuarios Analizados: ".$this->contador_filas."<br>"
                ."Usuarios Encontrados y actualizados: ".$this->contador_encontrados."<br>"
                ."<br><strong>Lista de Usuarios encontrados y actualizados: </strong><br><br>".$this->cadena."<br>");
            }else{
                DB::rollback();
                return redirect()->route('usuarios.importar')->with('alerta', 'No se pudo cargar el archivo. Verifique que no tenga caracteres extraños!'.$this->mensaje_errores);
            }
            

            
        }else{
            $title = "Error de acceso";
            return view('errors.access_denied');
        }
    }





}

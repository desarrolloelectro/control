<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Formulario;
use App\Formulario_detalle;
use App\Respuesta;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Auth;
use App\Usuario;
use Session;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');

class FormularioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $controlador = 'formularios';
            
        if(Auth::user()->nivel_form == '1'){
            $formularios = Formulario::orderBy('id','desc')->get();
            $title = "LISTA FORMULARIO";
            return view('formularios.index', compact('title','formularios','title','controlador'));
        }else{
            return view('errors.access_denied');
        }
    }

    public function create()
    {
        $controlador = 'formularios';

        if(Auth::user()->nivel_form == '1'){

            $accion = url('formularios/guardar');
            $metodo = method_field('POST');
            $title = "Crear Formulario";
            $boton = "Crear Formulario";
            return view('formularios.create',compact('accion','metodo','title','boton','controlador'));
            
        }else{
            return view('errors.access_denied');
        }
    }
    public function edit($id)
    {
        $controlador = 'formularios';

        if(Auth::user()->nivel_form == '1'){

            $formulario = Formulario::findOrFail($id);        
            $formulario_detalle = Formulario_detalle::where('formulario_id',$formulario->id)->orderBy('orden','asc')->get(); 
            $accion = url("formularios/actualizar/{$formulario->id}");
            $metodo = method_field('PUT');
            $title = "Actualizar formulario";
            $boton = "Actualizar";
            return view('formularios.create',compact('formulario_detalle','formulario','accion','metodo','title','boton','controlador'));

        }else{
            return view('errors.access_denied');
        }
    }
    public function store(Request $r)
    {
        $this->validate($r,[
            'nombre'=>'required',
            'descripcion'=>'required',
            'tipo_pregunta'=>'required',
            'pregunta'=>'required',
        ],[
            'nombre.required'=>"El campo nombre es requerido",
            'tipo_pregunta.required'=>"El Campo Tipo Pregunta es requerido",
            'pregunta.required'=>"El campo Pregunta es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $formulario = new Formulario;
        $formulario->nombre = $r->nombre;
        $formulario->descripcion = $r->descripcion;
        $formulario->user_new = Auth::id();
        $formulario->date_new = $dateonly;
        $formulario->created_at = $datehour;
        $formulario->updated_at = $datehour;
        $formulario->user_update = Auth::id();
        $formulario->save();

        $tipo_pregunta_array = $r->tipo_pregunta;
        $pregunta_array = $r->pregunta;

        if($tipo_pregunta_array != null){
            $n = count($tipo_pregunta_array);
            for ($i = 0; $i < $n; $i++ ) {

                $detalle = new Formulario_detalle;
                $detalle->formulario_id = $formulario->id;
                $detalle->tipo_pregunta = $tipo_pregunta_array[$i];
                $detalle->pregunta = $pregunta_array[$i];
                $detalle->orden = $i;
           
                $detalle->date_new = $dateonly;
                $detalle->created_at = $datehour;
                $detalle->updated_at = $datehour;
                $detalle->user_new = Auth::id();
                $detalle->user_update = Auth::id();

                $detalle->save();
            }
        }


        return redirect()->route('formularios.create')->with('mensaje', 'Registro ingresado con éxito!');

    }    
    public function update(Request $r, $id)
    {       
        $formulario = Formulario::findOrfail($id);

        $this->validate($r,[
            'nombre' => 'required',Rule::unique('formularios')->ignore($formulario->id),
            'descripcion'=>'',
            'estado'=>'',
        ],[
            'nombre.required'=>"El campo nombre es requerido",
            'nombre.unique'=>"El campo nombre ya existe",
            'estado'=>"El campo Estado es requerido"
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $formulario->nombre = $r->nombre;
        $formulario->descripcion = $r->descripcion;
        $formulario->estado = $r->estado;
        $formulario->updated_at = $datehour;
        $formulario->user_update = Auth::id();
        $formulario->save();

        $tipo_pregunta_array = $r->tipo_pregunta;
        $pregunta_array = $r->pregunta;

        if($tipo_pregunta_array != null){
            $n = count($tipo_pregunta_array);
            for ($i = 0; $i < $n; $i++ ) {

                $detalle = new Formulario_detalle;
                $detalle->formulario_id = $formulario->id;
                $detalle->tipo_pregunta = $tipo_pregunta_array[$i];
                $detalle->pregunta = $pregunta_array[$i];
           
                $detalle->date_new = $dateonly;
                $detalle->created_at = $datehour;
                $detalle->updated_at = $datehour;
                $detalle->user_new = Auth::id();
                $detalle->user_update = Auth::id();

                $detalle->save();
            }
        }

        //************ ACTUALIZAR PREGUNTAS ANTIGUAS *************** */

        $id_tabla = $r->id_tabla;
        $pregunta_tabla = $r->pregunta_tabla;
        $tipo_tabla = $r->tipo_tabla;

        if($id_tabla != null){
            $n = count($id_tabla);
            for ($i = 0; $i < $n; $i++ ) {

                $detalle = Formulario_detalle::where('id',$id_tabla[$i])->first();
                if($detalle != null){
                    $detalle->pregunta = $pregunta_tabla[$i];
                    $detalle->tipo_pregunta = $tipo_tabla[$i];
                    $detalle->orden = $i;
                    $detalle->updated_at = $datehour;
                    $detalle->user_update = Auth::id();
    
                    $detalle->save();
                }
                
            }
        }
        
        return redirect("formularios/{$formulario->id}/editar")->with('mensaje', 'Actualización realizada con éxito!');
    }


    public function show($id)
    {
        if(Auth::user()->nivel_form == '1'){

            $formulario = Formulario::findOrFail($id);
            $title = 'Detalle de formulario';

            return view('formularios.show',compact('title','formulario','usuario'));

        }else{
            return view('errors.access_denied');
        }
    }

    public function destroy($id)
    {    
        if(Auth::user()->nivel_form == '1'){
            $formulario = Formulario::findOrFail($id);
            try {
                $formulario->delete($id);
                return redirect()->route('formularios.index')->with('mensaje', 'Registro eliminado con éxito!');
            } 
            catch(QueryException $e) {
                return redirect()->route('formularios.index')->with('alerta', 'No se pudo eliminar el registro!');
            } 
        }else{
            return view('errors.access_denied');
        }
    }   

    public function guardar_respuestas(Request $r)
    {
        $this->validate($r,[
            'formulario_id'=>'required',
        ],[
            'formulario_id.required'=>"El campo Formulario es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $pregunta_array = $r->pregunta;

        if($pregunta_array != null){

            $respuesta = new Respuesta;
            $respuesta->formulario_id = $r->formulario_id;

            $n = count($pregunta_array);
            for ($i = 0; $i < $n; $i++ ) {

                $campo_pre = "pre".($i+1);
                $campo_res = "res".($i+1);

                $respuesta_request = 'respuesta_'.$pregunta_array[$i];

                $respuesta->$campo_pre = $pregunta_array[$i];
                $respuesta->$campo_res = $r->$respuesta_request;
                
            }

            $respuesta->date_new = $dateonly;
            $respuesta->created_at = $datehour;
            $respuesta->updated_at = $datehour;
            $respuesta->user_new = Auth::id();
            $respuesta->user_update = Auth::id();

            $respuesta->save();

        }
        return redirect()->route('principal.index')->with('mensaje', 'Registro ingresado con éxito!');

    }    

    public function eliminar_pregunta(Request $r)
    {    
        if(Auth::user()->nivel_form == '1'){
            $formulario_detalle = Formulario_detalle::findOrFail($r->id);
            try {
                $formulario_detalle->delete($r->id);
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

}

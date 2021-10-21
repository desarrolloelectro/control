<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dsi;
use App\DsiData;
use App\DsiDataAdvance;
use App\DsiDataDsm;
use App\DsiDataProduct;
use App\DsiPermission;
use App\Historial;
use App\Medio_pago;
use App\Tipo_identificacion;
use App\Tipo_factura;
use App\Tipo_documento;
use App\Categoria;
use App\Genero;
use App\Unidad;
use App\Iva_estado;
use App\DsiAudit;
use App\DsiProduct;
use DB;
use Auth;

class DsiDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $dev = false;//variable temporal para desarrollo
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listEmptyAdvance($dsi_id, $id)
    { 
        $permiso = DsiPermission::dsi_permiso(0,'dsi.data.view');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $dia_iva = DsiData::where('id',$id)->where('dsi_id',$dsi_id)->firstOrFail();
            $dia_iva->dsi_data_advances();
            $res = [];
            foreach($dia_iva->dsi_data_advances as $id => $dsi_data_advances){
                if($dsi_data_advances->saldo > 0){
                    $dsi_data_advances->fecha_recibo = custom_date_format($dsi_data_advances->fecha_recibo, "d/m/Y");
                    $dsi_data_advances->saldo = $dsi_data_advances->saldo;
                    $res[]=$dsi_data_advances;
                }
            }
            return $res;
        }else{
            return view('errors.access_denied');
        }
    }
    public function listProductAdvance(Request $request)
    {
        //dd($request);
        $permiso = DsiPermission::dsi_permiso(0,'dsi.data.view');
        if(Auth::user()->validar_permiso($permiso) || true){
            $p = DsiDataProduct::findOrFail($request->p);
            $p->dsi_data_all_advances();
            if(!empty($p->dsi_data_all_advances)){
                $res = [];
                foreach($p->dsi_data_all_advances as $id => $dsi_data_advances){ 
                        $dsi_data_advances->fecha_recibo = custom_date_format($dsi_data_advances->fecha_recibo, "d/m/Y");
                        $dsi_data_advances->value = $dsi_data_advances->pivot->value;
                        $dsi_data_advances->num_recibo = $dsi_data_advances->num_recibo;
                        $dsi_data_advances->fecha_recibo = $dsi_data_advances->fecha_recibo;
                        $dsi_data_advances->value = $dsi_data_advances->value;
                        $res[]=$dsi_data_advances;
                }
                return response()->json([
                    'success' => true,
                    'valor' => $p->valor,
                    'data' =>$res,
                    'message' => "ok"
                ]);
                return $p->dsi_data_advances;
            }else{
                return response()->json([
                    'ok' => false,
                    'message' => "error"
                ]);
            }
        }
        

    }
    
    public function asociateProductAdvance(Request $request)
    {
        //pendiente permiso
        $token1 = $request->_token;
        $token2 = csrf_token();
        
        $val_c_token = md5('?p='.$request->p.'&a='.$request->a);
        if ($token1 == $token2 &&  $request->c_token==$val_c_token){
            $dsi_data_advance = DsiDataAdvance::findOrFail($request->a);
            $dsi_data_product = DsiDataProduct::findOrFail($request->p);
            if($dsi_data_advance->saldo < $request->v){    
                return response()->json([
                    'ok' => false,
                    'message' => "Saldo insuficiente"
                ]);
            }
            $count_b = count($dsi_data_advance->dsi_data_all_products);
            $dsi_data_advance->dsi_data_all_products()->attach($dsi_data_product, [
                'state' => 1, 
                'value' => $request->v,
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => Auth::user()->coduser
             ]);
             $count_a = count($dsi_data_advance->dsi_data_all_products);
             $result = $count_a==$count_b;
            //$dsi_data_advance->dsi_data_product_id = $request->p;
            //$result = $dsi_data_advance->save();
            if($result){
                $listEmptyAdvance = $this->listEmptyAdvance($request->i,  $request->d);//i = dsi_id d = dsi_data_id
                return response()->json([
                    'success' => $result,
                    'data' => $listEmptyAdvance,
                    'message' => "Anticipo de ".custom_currency_format($request->v)." registrado"
                ]);
            }else{
                return response()->json([
                    'ok' => false,
                    'message' => "error1"
                ]);
            }
           
            
        }else{
            return response()->json([
                'ok' => false,
                'message' => "error2"
            ]);
        }
    }
    public function desaociateProductAdvance(Request $request)
    {
        //pendiente permiso
        $dsi_data_advance = DsiDataAdvance::findOrFail($request->dsi_advance_id);
        $dsi_data_advance->dsi_data_product_id = NULL;
        $result = $dsi_data_advance->save();
        return response()->json([
            'success' => $result,
            'message' => $result ? "ok" : "error"
        ]);
    }
    public function index(Request $request, $id)
    {
        $permiso = DsiPermission::dsi_permiso($id,'dsi.data.view');
        //dd($permiso);
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
        $controlador = "dsi.data";
        $subcon = 'dsi.data';
        $dsi = Dsi::find($id);
        $fields = Dsi::$fields_data;

        //if(Auth::user()->validar_permiso('con_access')){

            $valor = "";
            if(isset($_GET['search'])){
                $valor = $_GET['search'];
                session(['valor_session_dsi_data' => $valor]);
            }
            $valor = session('valor_session_dsi_data');
            $medio_pago_id = "";
            if(isset($_GET['medio_pago_id'])){
                $medio_pago_id = $_GET['medio_pago_id'];
                session(['medio_pago_session_dsi_data' => $medio_pago_id]);
            }
            $medio_pago_id = session('medio_pago_session_dsi_data');
            $porciones = explode("-", $valor);


            if(isset($porciones[1])){
                
                //$dsi_data = Dia_iva::where('tipodoc',$porciones[0])->where('numdoc',$porciones[1])->orderBy('id','desc')->paginate(50);

                $dsi_data = DsiData::query();
                $dsi_data = $dsi_data->select('dsi_data.*')
                ->where('dsi_data.dsi_id',$id)
                ->leftJoin('tipo_documentos as db2','dsi_data.tipodoc','db2.id')
                ->where('db2.codigo',$porciones[0])
                ->where('dsi_data.numdoc',$porciones[1]);

                if($medio_pago_id != '0' && $medio_pago_id != '' && $medio_pago_id != null){
                    $dsi_data = $dsi_data->where('dsi_data.mediopago',$medio_pago_id);
                }

                $dsi_data = $dsi_data->orderBy('dsi_data.id','desc')->paginate(50);


            }else{

                $dsi_data = DsiData::query();
                $dsi_data = $dsi_data->select('dsi_data.*')
                ->where('dsi_data.dsi_id',$id)
                ->leftJoin('tipo_documentos as db2','dsi_data.tipodoc','db2.id')
                ->where(function ($query) use ($valor){
                    $query->where('dsi_data.identificacion', 'LIKE', '%' . $valor . '%')
                    ->orWhere('dsi_data.id', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('tipofac', 'LIKE', '%' . $valor . '%')              
                    ->orWhere('dsi_data.numdoc', 'LIKE', '%' . $valor . '%')             
                    ->orWhere('db2.depto', 'LIKE', '%' . $valor . '%')              
                    ->orWhere('db2.codigo', 'LIKE', '%' . $valor . '%')              
                    ->orWhere('db2.ciudad', 'LIKE', '%' . $valor . '%');              
                    //->orWhere('lugar', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('fecha', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('descripcion', 'LIKE', '%' . $valor . '%')              
                    //->orWhere('mediopago', 'LIKE', '%' . $valor . '%')    ;          
                });

                if($medio_pago_id != '0' && $medio_pago_id != '' && $medio_pago_id != null){
                    $dsi_data = $dsi_data->where('dsi_data.mediopago',$medio_pago_id);
                }

                $dsi_data = $dsi_data->orderBy('dsi_data.id','desc')->paginate(50);
            }
                       

            $title = $dsi->name;
            $medio_pagos = Medio_pago::orderBy('id','asc')->get();

            if($valor != '' && $valor != null){
                $dsi_data->appends(['search' => $valor]);
            }

            if($medio_pago_id != '' && $medio_pago_id != null){
                $dsi_data->appends(['medio_pago_id' => $medio_pago_id]);
            }
            
            /*
            foreach($dsi_data as $row_id => $field_row){
                //dd($field_row->iva_estado);
                //$color = $field_row->iva_estado != null ? $field_row->iva_estado->color : '';
                $iva_estado = '<span class="span-estilo">1</span>';//'<span class="span-estilo" style = "background: '.$field_row->iva_estado != null ? $field_row->iva_estado->color : "".';">'.$field_row->iva_estado != null ? $field_row->iva_estado->nombre : "".'</span>';
                $field_row->iva_estado = $iva_estado;
            }
            <span class = 'span-estilo' style = "background: {{$dia_iva->iva_estado != null ? $dia_iva->iva_estado->color : ''}};">{{ $dia_iva->iva_estado != null ? $dia_iva->iva_estado->nombre : "" }}</span>
            <span class = 'span-estilo' style = "background: {{$dia_iva->banco_estado != null ? $dia_iva->banco_estado->color : ''}};">{{ $dia_iva->banco_estado != null ? $dia_iva->banco_estado->nombre : "" }}</span>
            <span class = 'span-estilo' style = "background: {{$dia_iva->caja2_estado != null ? $dia_iva->caja2_estado->color : ''}};">{{ $dia_iva->caja2_estado != null ? $dia_iva->caja2_estado->nombre : "" }}</span>
*/
            $url_paginacion = route('dsi.data.index',['id' => $id]);

            return view('dsi.data.index',['id' => $id], compact('dsi_data','dsi','fields', 'medio_pagos','valor','medio_pago_id','url_paginacion','title','controlador','subcon'));        
       /*
       Revisar los campos y crear la tabla de forma dinamica, mirar la posibilidad de reducir a columna fields a una sola para manejar estados con 1 y 0 y saber si se usa o no el campo
       eso facilitará ell uso de campos extra
       */ 
      /*
            $dsi_data = DsiData::where('dsi_id', $id)->get();
            $table_name = $dsi_data->getTable();
            $dsi_data_fields = DB::getSchemaBuilder()->getColumnListing($table_name);
            dd($dsi_data);
            if(in_array('genero', $dsi_data_fields))
            echo "Si";
            else
            echo "No";
            echo $request->ip();
            //dd($id);
            */
        }else{
            return view('errors.access_denied');
        }
    }
    public function export(Request $request, $id)
    {
        echo "Export";
        dd($id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscarProductos(Request $request)
    {
        if(Dsi::$depurar) DB::enableQueryLog();
        $productos = DsiProduct::whereRaw("nombre LIKE '%$request->value%'")
            ->orWhereRaw("linea LIKE '%$request->value%'")
            ->orWhereRaw("referencia LIKE '%$request->value%'")
            ->orWhereRaw("marca LIKE '%$request->value%'")
            ->paginate(10);
            //$query->orWhere('valor', '=', $request->value); para value usar rango min entre max
        if(Dsi::$depurar) $log = DB::getQueryLog();
        // if(Dsi::$depurar) var_dump($log);
        
        $pag = $this->getView($productos, 'layouts.pagination.page');
        
        return response()->json([
            'success' => true,
            'productos' => $productos,
            'pagination' => $pag
        ]);
        //return $productos;
    }
    public function getView($model, $url){
        ob_start();
        echo $model->render($url);
        $resp = ob_get_clean();
        return $resp;
    }
    public function create(Dsi $dsi)
    {

        $controlador = "dsi.data.create";
        $subcon = 'dsi.data.create';

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);
        $fecha_sistema = $dateonly;
        $permiso = DsiPermission::dsi_permiso(0,'dsi.data.create');
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            $accion = route('dsi.data.store', ['dsi_id' => $dsi->id]);
            $metodo = method_field('POST');
            $titulo = $dsi->name;
            $titulo2 = "Nuevo";
            $boton = "Guardar";
            $boton2 = "Guardar y continuar editando";

            $medio_pagos = Medio_pago::orderBy('id','asc')->get();

            $tipo_identificaciones = Tipo_identificacion::orderBy('id','asc')->get();
            $tipo_facturas = Tipo_factura::orderBy('id','asc')->get();
            $tipo_documentos = Tipo_documento::orderBy('id','asc')->get();
            $categorias = Categoria::orderBy('id','asc')->get();
            $generos = Genero::orderBy('id','asc')->get();
            $unidades = Unidad::orderBy('id','asc')->get();
            $iva_estados = Iva_estado::orderBy('id','asc')->get();
            $dia_iva = new DsiData();
            
            /*
            foreach($dsi_metas as $dsi_meta){
                if(in_array($dsi_meta->id,$meta_fields)){
                    echo $dsi_meta->id;
                    echo $dsi_meta->field_name;
                    echo $dsi_meta->attribs;
                    $dia_iva->dsi_meta_value($dsi_meta->id);
                    if(isset($dia_iva->dsi_meta_value->value))
                        echo ($dia_iva->dsi_meta_value->value);
                        
                    echo "<br>";
                }
            }
            */
            $enable_meta = DSI::$meta;
            $ayuda = false;
            $documentsm = ['RJS8'=>'RJS8 :: Recibo Sistema', 'REC8'=>'REC8 :: Recibo Manual'];
            $documentdsm = 'PFDI';
            $tiposventa = [];
            $date = date("Y-m-d");
            if($date==$dsi->date){
                $tiposventa[] = 'Contado';
                $anticipo = false;
            }else{
                $tiposventa[] = 'Anticipo';
                $anticipo = true;
            }
            return view('dsi.data.create',compact('anticipo', 'dsi', 'tipo_identificaciones','tipo_facturas','tipo_documentos',
            'categorias','generos','unidades','iva_estados','fecha_sistema','medio_pagos',
        'accion','metodo','titulo','titulo2','boton','boton2','controlador','subcon','dia_iva','documentsm','documentdsm', 'ayuda', 'tiposventa', 'enable_meta'));
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
    public function store(Request $request, $dsi_id)
    {
        $permiso_create = DsiPermission::dsi_permiso(0,'dsi.data.create');
        if(Auth::user()->validar_permiso($permiso_create)){
        $boton = "Guardar";
        $boton2 = "Guardar y continuar editando";
        //var_dump($dsi_id);
       
        
        
        $this->validate($request,[
            'nombre'=>'required',
        ],[
            'nombre.required'=>"El campo Nombre es requerido",
        ]);

        date_default_timezone_set('America/Bogota');
        $fechahora=time();
        $dateonly=date("Y-m-d", $fechahora);
        $datehour= date("Y-m-d H:i:s", $fechahora);

        $dsi_data = new DsiData;
        
        $dsi_data->dsi_id = $dsi_id;
        //Inicio campos fijos
        $dsi_data->tipoventa = $request->tipoventa;
        $dsi_data->tipoid = $request->tipoid;
        $dsi_data->identificacion = $request->identificacion;
        $dsi_data->nombre = $request->nombre;
        $datetime = date("Y-m-d H:i:s");
        $dsi_data->date_new = date("Y-m-d");
        $dsi_data->user_new = Auth::user()->coduser;
        $dsi_data->created_at = $datetime;
        //Fin campos fijos
        //Inicio Anticipo
        if($request->tipoventa == "Anticipo"){
            $result = $dsi_data->save();
            if($result){
                $dsi_data = DsiData::where('dsi_id',$dsi_id)
                            ->where('identificacion',$request->identificacion)
                            ->where('created_at',$datetime)
                            ->where('user_new',Auth::user()->coduser)
                            ->firstorFail();
                if(isset($request->ant_tipo_recibo)){
                    foreach($request->ant_tipo_recibo as $id => $anticipo){
                        $dsi_data_advance = new DsiDataAdvance;
                        $dsi_data_advance->dsi_data_id = $dsi_data->id;
                        $dsi_data_advance->tipo_recibo = $request->ant_tipo_recibo[$id];
                        $dsi_data_advance->num_recibo = $request->ant_num_recibo[$id];
                        $dsi_data_advance->valor_recibo = $request->ant_vr_recibo[$id];
                        $dsi_data_advance->fecha_recibo = $request->ant_fecha_recibo[$id];
                        $dsi_data_advance->cliente_id = $request->ant_cliente_id[$id];
                        $dsi_data_advance->cliente_nombre = $request->ant_cliente_nombre[$id];
                        $dsi_data_advance->created_by = Auth::user()->coduser;
                        $dsi_data_advance->save();
                    }
                }
                if(isset($request->dsi_ant_num_dsm)){
                    foreach($request->dsi_ant_num_dsm as $id => $num_dsm){
                        $dsi_data_dsm = new DsiDataDsm;
                        $dsi_data_dsm->dsi_data_id = $dsi_data->id;
                        $dsi_data_dsm->dsm =  $request->dsi_ant_dsm[$id];
                        $dsi_data_dsm->num_dsm = $request->dsi_ant_num_dsm[$id];
                        $dsi_data_dsm->save();
                        if(isset($request->productItemnombre[$num_dsm])){
                            foreach($request->productItemnombre[$num_dsm] as $id2 => $anticipo){
                                $dsi_data_product = new DsiDataProduct;
                                $dsi_data_product->dsi_data_dsm_id = $dsi_data_dsm->id;
                                $dsi_data_product->nombre = $request->productItemnombre[$num_dsm][$id2];
                                $dsi_data_product->referencia = $request->productItemreferencia[$num_dsm][$id2];
                                $dsi_data_product->serial = $request->productItemserial[$num_dsm][$id2];
                                $dsi_data_product->valor = $request->productItemvalor[$num_dsm][$id2];
                                $dsi_data_product->linea = $request->productItemlinea[$num_dsm][$id2];
                                $dsi_data_product->save();
                            }
                        }
                    }
                }
            }
        }
        //Fin Anticipo
        

        $dsi_data->tipofac = $request->tipofac;
        $dsi_data->tipodoc = $request->tipodoc;
        $dsi_data->numdoc = $request->numdoc;
        $dsi_data->fecha = $request->fecha;
        $dsi_data->categoria = $request->categoria;
        $dsi_data->genero = $request->genero;
        $dsi_data->cantidad = $request->cantidad;
        $dsi_data->unidad = $request->unidad;
        $dsi_data->descripcion = $request->descripcion;
        $dsi_data->vrunit = $request->vrunit;
        $dsi_data->vrtotal = $request->vrtotal;
        $dsi_data->mediopago = $request->mediopago;
        $dsi_data->numsoporte = $request->numsoporte;
        $dsi_data->obs = $request->obs;
        //$dsi_data->fechaentrega = $request->fechaentrega;
        $dsi_data->pvppublico = $request->pvppublico;
        //Inicio Estados
        $dsi_data->estado_id = $request->estado_id;
        $dsi_data->banco_estado_id = 3;
        $dsi_data->caja2_estado_id = 5;
        //Fin Estados
        $file = $request->urlimagen;
        if($file != null && $file != ''){
            $filename = uniqid();
            $extension = $file->getClientOriginalExtension();
            $picture = date('YmdHis').'-'.$filename.".$extension";

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
            $dsi_data->urlimagen = $picture;
        }
        //Inicio campos de auditoría
        //$dsi_data->date_new = $dateonly;
        //$dsi_data->user_new = Auth::id();
        $dsi_data->user_update = Auth::id();
        $dsi_data->created_at = $datehour;
        $dsi_data->updated_at = $datehour;
        //Fin campos de auditoría
        $result2 = $dsi_data->save();
        //$request->dsi_meta;
        if($result2){
            if($dsi_data->id != 0){
                $id = $dsi_data->id;
            }else{
                $dsi_data = DsiData::where('dsi_id',$dsi_id)
                                ->where('identificacion',$request->identificacion)
                                ->where('created_at',$datetime)
                                ->where('user_new',Auth::user()->coduser)
                                ->firstorFail();
                $id = $dsi_data->id;
            }
            $enable_meta = DSI::$meta;
            if($enable_meta){
                //aqui la rutina de guardado de meta datos
            }
            if($request->submit == $boton){
                return redirect()->route('dsi.data.index',['id' => $dsi_id])->with('mensaje', 'Registro ingresado con éxito!');
            }else if($request->submit == $boton2){
                return redirect()->route('dsi.data.edit',['dsi_id' => $dsi_id, 'id' => $id])->with('mensaje', 'Registro ingresado con éxito!');
            }
        }else{
            return redirect()->route('dsi.data.index',['id' => $dsi_id])->with('alerta', 'Hubo un error al ingresadar los datos');
        }
    }else{
        return view('errors.access_denied');
    }

        //dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($dsi_id,$id)
    {
        $permiso = \App\DsiPermission::dsi_permiso($dsi_id,'dsi.data.show');
        if(Auth::user()->validar_permiso($permiso) || $this->dev){
            return view('errors.access_denied');
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
    public function edit($dsi_id,$id)
    {
        //dd($dsi_id." - ".$id);
        /**
         * Camel Case
         * nombreFunction
         * 
         * Snake
         * nombre_funcion
         * 
         */
        $controlador = "dsi.data.edit";
        $subcon = 'dsi.data.edit';
        $permiso_dsi_view = DsiPermission::dsi_permiso(0,'dsi.view');
        $permiso_edit = DsiPermission::dsi_permiso($dsi_id,'dsi.data.edit');
        $permiso_authorize = DsiPermission::dsi_permiso($dsi_id,'dsi.data.authorize');
        $permiso_reverse = DsiPermission::dsi_permiso($dsi_id,'dsi.data.reverse');
        
        if(Auth::user()->validar_permiso($permiso_dsi_view) && Auth::user()->validar_permiso($permiso_edit) || Auth::user()->validar_permiso( $permiso_authorize) || Auth::user()->validar_permiso($permiso_reverse)) {
            $dsi = Dsi::find($dsi_id);
            $meta_fields = json_decode($dsi->meta_fields,true);
            $dia_iva = DsiData::where('id',$id)->where('dsi_id',$dsi_id)->firstOrFail();
            $dia_iva->dsi_metas();
            $dsi_metas = $dia_iva->dsi_metas;

            if($dia_iva->caja2_estado_id == 6 && !Auth::user()->validar_permiso($permiso_reverse)){
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

            $accion = route('dsi.data.update',['dsi_id'=> $dia_iva->dsi_id, 'id'=> $dia_iva->id]);
            //dsi/data/{dsi_id}/update/{id}
            $metodo = method_field('POST');
            $titulo = "Actualizar Día sin IVA Noviembre";
            $titulo2 = "Editar";
            $boton = "Actualizar";
            $boton2 = "Actualizar y continuar editando";
            $enable_meta = DSI::$meta;
            $ayuda = false;
            $documentsm = ['RJS8'=>'RJS8 :: Recibo Sistema', 'REC8'=>'REC8 :: Recibo Manual'];
            $documentdsm = 'PFDI';
            $tiposventa = [];
            $date = date("Y-m-d");
            $tiposventa[] = 'Contado';
            $tiposventa[] = 'Anticipo';
            $dia_iva->dsi_data_advances();
            $dia_iva->dsi_data_dsms();

            $dsi_data_advances = $dia_iva->dsi_data_advances;
            $dsi_data_dsms = $dia_iva->dsi_data_dsms;
            
            foreach($dia_iva->dsi_data_dsms as $id => $dsms){
                $dia_iva->dsi_data_dsms[$id]->dsi_data_products();             
                //$dsms->dsi_data_products();                
            }
            //dd($dia_iva->dsi_data_advances);
            
            if ($dia_iva->tipoventa == "Anticipo"){
                $date = date("Y-m-d");
                if($date==$dsi->date){
                    $anticipo = false;
                }else{
                    $anticipo = true;
                }
            }else{
                $anticipo = false;
            }
            return view('dsi.data.create',compact('anticipo', 'titulo','titulo2','historicos','tipo_identificaciones',
            'tipo_facturas','tipo_documentos','categorias','generos','unidades','iva_estados','medio_pagos',
            'dia_iva','accion','metodo','boton','controlador','subcon', 'dsi', 'meta_fields', 'dsi_metas', 'ayuda', 
            'documentsm', 'documentdsm', 'tiposventa', 'boton2','enable_meta'));  

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
    public function update(Request $request, $dsi_id, $id)
    {
        //dd([$request->all(), $dsi_id, $id]);
        $dsi_data = DsiData::where('dsi_id',$dsi_id)->where('id',$id)->firstOrFail();
        //Inicio Anticipo
        //if(isset($request->tipoventa))
        if($request->tipoventa == "Anticipo" || $dsi_data->tipoventa == "Anticipo"){
            //dd([$request->all(), $dsi_id, $id]);
                if(isset($request->ant_tipo_recibo)){
                    foreach($request->ant_tipo_recibo as $id => $anticipo){
                        if($request->ant_id[$id]==""){
                            $dsi_data_advance = new DsiDataAdvance;
                            $dsi_data_advance->dsi_data_id = $dsi_data->id;
                            $dsi_data_advance->tipo_recibo = $request->ant_tipo_recibo[$id];
                            $dsi_data_advance->num_recibo = $request->ant_num_recibo[$id];
                            $dsi_data_advance->valor_recibo = $request->ant_vr_recibo[$id];
                            $dsi_data_advance->fecha_recibo = $request->ant_fecha_recibo[$id];
                            $dsi_data_advance->cliente_id = $request->ant_cliente_id[$id];
                            $dsi_data_advance->cliente_nombre = $request->ant_cliente_nombre[$id];
                            $dsi_data_advance->created_by = Auth::user()->coduser;
                            $dsi_data_advance->save();
                        }
                    }
                }
               
                if(isset($request->dsi_ant_num_dsm)){
                    foreach($request->dsi_ant_num_dsm as $id => $num_dsm){
                        if(isset($request->dsi_ant_num_dsm[$id]) && $request->dsi_ant_num_dsm[$id]!=""){
                            if(isset($request->dsi_ant_dsm_id[$id]) && $request->dsi_ant_dsm_id[$id]==""){
                                $dsi_data_dsm = new DsiDataDsm;
                                $dsi_data_dsm->dsi_data_id = $dsi_data->id;
                                $dsi_data_dsm->dsm = (isset($request->dsi_ant_dsm[$id]) && $request->dsi_ant_dsm[$id]!=null) ? $request->dsi_ant_dsm[$id] : "PFDI";
                                $dsi_data_dsm->num_dsm = $request->dsi_ant_num_dsm[$id];
                                $dsi_data_dsm->save();
                            
                                if(isset($request->productItemnombre[$num_dsm])){
                                    foreach($request->productItemnombre[$num_dsm] as $id2 => $anticipo){
                                        if($request->productItemid[$num_dsm][$id2]==""){
                                            $dsi_data_product = new DsiDataProduct;
                                            $dsi_data_product->dsi_data_dsm_id = $dsi_data_dsm->id;
                                            $dsi_data_product->nombre = $request->productItemnombre[$num_dsm][$id2];
                                            $dsi_data_product->referencia = $request->productItemreferencia[$num_dsm][$id2];
                                            $dsi_data_product->serial = $request->productItemserial[$num_dsm][$id2];
                                            $dsi_data_product->valor = $request->productItemvalor[$num_dsm][$id2];
                                            $dsi_data_product->linea = $request->productItemlinea[$num_dsm][$id2];
                                            $dsi_data_product->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $dsi_data->dsi_data_dsms();
                if(isset($dsi_data->dsi_data_dsms)){
                     
                    foreach($dsi_data->dsi_data_dsms as $id => $dsi_data_dsm){
                        if(isset($request->dsi_ant_dsm_id[$id]) && $request->dsi_ant_dsm_id[$id]!=""){
                            $dsi_data_dsm = DsiDataDsm::find($request->dsi_ant_dsm_id[$id]);
                            if(isset($request->dsi_ant_num_dsm[$id])) $dsi_data_dsm->num_dsm = $request->dsi_ant_num_dsm[$id];
                            $dsi_data_dsm->save();
                        }
                        $num_dsm = $dsi_data_dsm->num_dsm;
                        //if(!isset($request->dsi_ant_num_dsm[$id]) || !in_array($num_dsm,$request->dsi_ant_num_dsm)){
                           // var_dump($dsi_data_dsm);
                           // dd($request->all());
                            if(isset($request->productItemnombre[$num_dsm])){
                                foreach($request->productItemnombre[$num_dsm] as $id2 => $anticipo){
                                    //dd([$request->all(),$dsi_data_dsm, $dsi_id, $id, $id2, $anticipo]);
                                    if(isset($request->productItemid[$num_dsm][$id2]) && $request->productItemid[$num_dsm][$id2]==""){
                                        $dsi_data_product = new DsiDataProduct;
                                        $dsi_data_product->dsi_data_dsm_id = $dsi_data_dsm->id;
                                        $dsi_data_product->nombre = $request->productItemnombre[$num_dsm][$id2];
                                        $dsi_data_product->referencia = $request->productItemreferencia[$num_dsm][$id2];
                                        $dsi_data_product->serial = $request->productItemserial[$num_dsm][$id2];
                                        $dsi_data_product->valor = $request->productItemvalor[$num_dsm][$id2];
                                        $dsi_data_product->linea = $request->productItemlinea[$num_dsm][$id2];
                                        $dsi_data_product->save();
                                    }
                                }
                            }
                        //}
                    }
                }
            
        }
        //Fin Anticipo
        if(isset($request->tipoid)) $dsi_data->tipoid = $request->tipoid;
        if(isset($request->identificacion)) $dsi_data->identificacion = $request->identificacion;
        if(isset($request->nombre)) $dsi_data->nombre = $request->nombre;
        if(isset($request->tipofac)) $dsi_data->tipofac = $request->tipofac;
        if(isset($request->tipodoc)) $dsi_data->tipodoc = $request->tipodoc;
        if(isset($request->numdoc)) $dsi_data->numdoc = $request->numdoc;
        if(isset($request->lugar)) $dsi_data->lugar = $request->lugar;
        if(isset($request->fecha)) $dsi_data->fecha = $request->fecha;
        if(isset($request->categoria)) $dsi_data->categoria = $request->categoria;
        if(isset($request->genero)) $dsi_data->genero = $request->genero;
        if(isset($request->cantidad)) $dsi_data->cantidad = $request->cantidad;
        if(isset($request->unidad)) $dsi_data->unidad = $request->unidad;
        if(isset($request->descripcion)) $dsi_data->descripcion = $request->descripcion;
        if(isset($request->vrunit)) $dsi_data->vrunit = $request->vrunit;
        if(isset($request->vrtotal)) $dsi_data->vrtotal = $request->vrtotal;
        if(isset($request->mediopago)) $dsi_data->mediopago = $request->mediopago;
        if(isset($request->numsoporte)) $dsi_data->numsoporte = $request->numsoporte;
        if(isset($request->fechaentrega)) $dsi_data->fechaentrega = ($request->fechaentrega!="") ? $request->fechaentrega : null;
        if(isset($request->pvppublico)) $dsi_data->pvppublico = $request->pvppublico;
        if(isset($request->obs)) $dsi_data->obs = $request->obs;
        if(isset($request->factura)) $dsi_data->factura = $request->factura;
        if(isset($request->estado_id)) $dsi_data->estado_id = $request->estado_id;
        if(isset($request->banco_estado_id)) $dsi_data->banco_estado_id = $request->banco_estado_id;
        if(isset($request->caja2_estado_id)) $dsi_data->caja2_estado_id = $request->caja2_estado_id;
        
        $dsi_data->user_update = Auth::id();
        $result = $dsi_data->save();

        $enable_meta = DSI::$meta;
        if($enable_meta){
            //rutinas actualizar campos adicionales
        }
        //dd([$dsi_data,$result,$enable_meta]);
        $boton = "Actualizar";
        $boton2 = "Actualizar y continuar editando";
        if($request->submit == $boton){
            return redirect()->route('dsi.data.index',['id'=>$dsi_id])->with('mensaje', "El Registro fué actualizado con éxito!");
        }else if($request->submit == $boton2){
            return redirect()->route('dsi.data.edit',['dsi_id' => $dsi_id, 'id' => $dsi_data->id])->with('mensaje', 'Registro actualizado con éxito!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($dsi_id, $id)
    {
        $dsi_data = DsiData::where('dsi_id',$dsi_id)->where('id',$id)->firstOrFail();
    }
    public function restore($dsi_id, $id)
    {
        $dsi_data = DsiData::where('dsi_id',$dsi_id)->where('id',$id)->firstOrFail();
    }
    public function history($dsi_id, $id)
    {
        $dsi = Dsi::findOrFail($dsi_id);
        $dsi_data = DsiData::where('dsi_id',$dsi_id)->where('id',$id)->firstOrFail();
        $fields_data = Dsi::$fields_data;
        $valor = "";
            if(isset($_GET['search'])){
                $valor = $_GET['search'];
                session(['valor_session_h' => $valor]);
            }
            $valor = session('valor_session_h');

        $histories = DsiAudit::where('context_id2', '=', $id)
            ->where('context_id', '=', $dsi_id)    
            ->where('context', '=', 'dsi_data')    
            ->where(function ($query) use ($valor){
            $query->where('audit', 'LIKE', '%' . $valor . '%')          
            ->orWhere('user', 'LIKE', '%' . $valor . '%')          
            ->orWhere('date', 'LIKE', '%' . $valor . '%');          
        })->paginate();       
        $title = $dsi->name;
        $title2 = "Historial de cambios";
        $url_paginacion = route('dsi.data.history', ['dsi_id' => $dsi_id, 'id' => $id]);
        return view('dsi.data.history',compact('dsi_data','dsi_id','id', 'fields_data','histories','title','title2','valor', 'url_paginacion'));       
    }
}

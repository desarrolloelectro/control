@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($gasto)){
    $nombre = $gasto->nombre;
    $agencia_id = $gasto->agencia_id;
    $estado_id = $gasto->estado_id;
    $tipo_gasto_id = $gasto->tipo_gasto_id;
    $num_gasto = $gasto->num_gasto;
    $nombre = $gasto->nombre;
    $descripcion = $gasto->descripcion;
    $fecha_sistema = $gasto->date_new;
    $obs = $gasto->obs;
    $obs_auditoria = $gasto->obs_auditoria;
    $obs_revisoria = $gasto->obs_revisoria;
    $area_id = $gasto->area_id;
    $tipo_identificacion_id = $gasto->tipo_identificacion_id;
    $identificacion = $gasto->identificacion;
    $cotizacion_id = $gasto->cotizacion_id;   
    $dv = $gasto->dv;
    $razon = $gasto->razon;
    $factura = $gasto->factura;
    $codigo = $gasto->codigo;
    $valor_solicitado = $gasto->valor_solicitado;
    $valor_autorizado = $gasto->valor_autorizado;

    $banco_id = $gasto->banco_id;
    $tipo_pago_id = $gasto->tipo_pago_id;
    $tipo_doc_audi = $gasto->tipo_doc_audi;
    $num_doc_audi = $gasto->num_doc_audi;
    $tipo_doc_audi = $gasto->tipo_doc_auditoria != null ? $gasto->tipo_doc_auditoria->tipo.' :: '.$gasto->tipo_doc_auditoria->nombre : "NO DEFINE";


}else{
    $nombre ="";
    $agencia_id ="";
    $estado_id ="";
    $tipo_gasto_id ="";
    $num_gasto ="";
    $nombre ="";
    $descripcion ="";
    $obs ="";
    $area_id ="";
    $tipo_identificacion_id ="";
    $obs_auditoria ="";
    $obs_revisoria ="";
    $identificacion ="";
    $cotizacion_id ="";    
    $dv ="";
    $razon ="";
    $factura ="";
    $codigo ="";
    $valor_solicitado ="";
    $valor_autorizado ="";    

    $banco_id ="";    
    $tipo_pago_id ="";    
    $tipo_doc_audi ="";    
    $num_doc_audi ="";  
}
if(isset($cotizacion)){

    $id_cotizacion = $cotizacion->id;
    $descripcion_cotizacion = $cotizacion->descripcion;
    $agencia_cotizacion = $cotizacion->agencia != null ? $cotizacion->agencia->agennom : "";
    $tipo_gasto_cotizacion = $cotizacion->tipo_gasto != null ? $cotizacion->tipo_gasto->tipo.' :: '.$cotizacion->tipo_gasto->nombre : "";
    $estado_cotizacion = $cotizacion->estado != null ? $cotizacion->estado->nombre : "";
    $valor_cotizacion = $cotizacion->valor_autorizado($cotizacion->id);
    $fecha_cotizacion = $cotizacion->created_at;

}else{

    $id_cotizacion = "";
    $descripcion_cotizacion = "";
    $agencia_cotizacion = "";
    $tipo_gasto_cotizacion = "";
    $valor_cotizacion = "";
    $fecha_cotizacion = "";
    $estado_cotizacion = "";
}
?>

<!-- AQUI CIERRAN LAS VARIABLES  -->


<div class="app-title">
    <div>
        <h1>{{ $titulo }}</h1>
    </div>
</div>

<div class = "row">
    <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h6>Para continuar debe corregir los siguientes errores:</h6>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h3 class="h5-dark"> Usuario Registro</h3>
            <div class = "table-responsive">
                    <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                        <tr>
                            <td class="bold">Nombre</td>
                            <td>
                                <input readonly required type="text" class="form-control" value="{{ $usuario->nombre }}">
                            </td>
                            <td class="bold">Celular</td>
                            <td>
                                <input readonly required type="text" class="form-control" value="{{ $usuario->telefono }}">

                            </td>
                            <td class="bold">Doc. Cotizacion</td>
                            <td>
                                <input readonly required type="text" class="form-control" value="{{ $usuario->cedula }}">  
                            </td>
                        </tr>
                        <tr>
                            <td class="bold">Fecha</td>
                            <td>
                                <input readonly required type="text" class="form-control" value="{{ $fecha_sistema }}">
                            </td>
                            <td class="bold">Agencia</td>
                            <td>
                                <input readonly required type="text" class="form-control" value="{{ $usuario->agencia_detalle($usuario->agencia) }}">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- AQUI INICIA EL FORMULARIO  -->
            <form method="POST" id = "formulario" action="{{ $accion }}" files="true" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ $metodo }}
                @isset($opcion)
                <input type="hidden" id = "opcion" name = "opcion" value = "{{$opcion}}">
                @endisset
                <h3 class="h5-dark"> Gasto</h3>

                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Agencia <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input type="text" readonly class = "form-control" value = "{{ $gasto->agencia != null ? $gasto->agencia->agennom : '' }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class = "row">
                            <div class = "col-md-8">
                                <div class="form-group">
                                    <label>Tipo Documento <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                                    <input type="text" readonly class = "form-control" value = "{{ $gasto->tipo_gasto != null ? $gasto->tipo_gasto->nombre : '' }}">
                                </div>
                            </div>
                            <div class = "col-md-4">
                                <div class="form-group">
                                    <label>No. Documento</label>
                                    <input type = "number" readonly class="form-control" value = "{{ old('num_gasto',$num_gasto) }}">
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Area <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input type="text" readonly class = "form-control" value = "{{ $gasto->area != null ? $gasto->area->nombre : '' }}">
                        </div>
                    </div>
                </div>

                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Tipo Identificación <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input type="text" readonly class = "form-control" value = "{{ $gasto->tipo_identificacion != null ? $gasto->tipo_identificacion->nombre : '' }}">
                        </div>
                    </div>                                     
                    <div class = "col-md-4">
                        <div class = "row">
                            <div class = "col-md-8">
                                <div class="form-group">
                                    <label>Identificación</label>
                                    <input readonly type = "number" class="form-control" value = "{{ old('identificacion',$identificacion) }}">
                                </div>
                            </div>
                            <div class = "col-md-4">
                                <div class="form-group">
                                    <label>DV</label>
                                    <input readonly type = "number" class="form-control" value = "{{ old('dv',$dv) }}">
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Nombre / Razón Social</label>
                            <input readonly type = "text" class="form-control" value = "{{ old('razon',$razon) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>No. Factura</label>
                            <input readonly type = "number" class="form-control" value = "{{ old('factura',$factura) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Código Interno / Contrato / Placa</label>
                            <input readonly type = "number" class="form-control" value = "{{ old('codigo',$codigo) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Valor Solicitado</label>
                            <input readonly type = "number" class="form-control" value = "{{ old('valor_solicitado',$valor_solicitado) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Detalle</label>
                            <textarea readonly rows="3" class="form-control">{{ old('descripcion',$descripcion) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Tipo Pago</label>
                            <input readonly type = "text" class="form-control" value = "{{ $gasto->tipo_pago != null ? $gasto->tipo_pago->nombre : '' }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Banco</label>
                            <input readonly type = "text" class="form-control" value = "{{ $gasto->banco != null ? $gasto->banco->nombre.' :: '.$gasto->banco->num_cuenta : 'NO DEFINE'  }}">
                        </div>
                    </div>
                </div>
                
                <h3 class="h5-dark"> Autorización / Cotización Cargada</h3>
                
                <div class = "table-responsive">
                        <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                            <tr>
                                <td class="bold">ID</td>
                                <td>
                                    <input type="hidden" id = "id_cotizacion" name = "id_cotizacion" value="{{ $id_cotizacion }}">
                                    <input readonly type="text" class="form-control" id = "id_cotizacion_nombre" name = "id_cotizacion_nombre" value="{{ $id_cotizacion }}">
                                </td>
                                
                                <td class="bold">Agencia</td>
                                <td>
                                    <input readonly type="text" class="form-control" id = "agencia_cotizacion" value="{{ $agencia_cotizacion }}">  
                                </td>
                                <td class="bold">Tipo Documento</td>
                                <td>
                                    <input readonly type="text" class="form-control" id = "tipo_gasto_cotizacion" value="{{ $tipo_gasto_cotizacion }}">
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <td class="bold">Estado</td>
                                <td>
                                    <input readonly type="text" class="form-control" id = "estado_cotizacion" value="{{ $estado_cotizacion }}">
                                </td>
                                <td class="bold">Valor Autorizado</td>
                                <td>
                                    <input readonly type="text" class="form-control" id = "valor_cotizacion" value="{{ $valor_cotizacion }}">
                                </td>
                                <td class="bold">Fecha</td>
                                <td>
                                    <input readonly type="text" class="form-control" id = "fecha_cotizacion" value="{{ $fecha_cotizacion }}">
                                </td>
                            </tr>
                            <tr>
                                <td class="bold">Detalle</td>
                                <td colspan = "5">
                                    <input readonly type="text" class="form-control" id = "descripcion_cotizacion" value="{{ $descripcion_cotizacion }}">

                                </td>
                            </tr>
                        </table>
                </div>
                
                <h3 class="h5-dark"> Archivos Adjuntos</h3>
                
                <div class="row">
                    <div class="col-md-12">
                    <div class="table-responsive">
                        <table id = "tabla_agregar" class="table table-list table-striped table-bordered">
                            <thead style = "font-weight:bold!important;">
                                <tr>
                                    <th>DESCRIPCIÓN ARCHIVO</th>
                                    <th style = "width:400px;">ARCHIVO</th>
                                    <th style = "width:50px;">ACCIONES</th>
                                </tr>
                            </thead> 
                        <tbody>

                            @isset($gasto_detalle)
                                @if ($gasto_detalle->isNotEmpty())
                                    @foreach($gasto_detalle as $index => $detalle)
                                    <tr>
                                        <td> 
                                            <input required readonly type="text" class = "form-control" value = "{{ $detalle->nombre }}">
                                        </td>
                                        <td>
                                            <input readonly style = "padding: 0.15rem 0.15rem;" type="text" class = "form-control" value = "Archivo Seleccionado">
                                        </td>
                                        <td>
                                            <center style = "padding-top:7px!important">
                                            <a class="btn2 btn-info" target = "_blank" href="{{ asset('uploads/gastos') }}/{{$detalle->urlarchivo}}" class = "bold"><i class = "fa fa-search-plus"></i></a>
                                             </center>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            @endisset


                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>

                @isset($gasto)

                <h3 class="h5-dark"> Auditoría y Revisoría</h3>
               
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Observaciones Auditoría</label>
                            <textarea readonly rows="3" class="form-control">{{ old('obs_auditoria',$obs_auditoria) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Tipo Documento</label>
                            <input readonly type = "text" class="form-control" value = "{{ old('tipo_doc_audi',$tipo_doc_audi) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>No. Documento</label>
                            <input readonly type = "text"  class="form-control" value = "{{ old('num_doc_audi',$num_doc_audi) }}">
                        </div>
                    </div>
                </div>
                
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Observaciones Revisoría</label>
                            <textarea readonly rows="3" class="form-control">{{ old('obs_revisoria',$obs_revisoria) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class = "row">
                    
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Valor Autorizado</label>
                            <input type = "number" readonly class="form-control" value = "{{ old('valor_autorizado',$valor_autorizado) }}">
                        </div>
                    </div>
                    
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Estado <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input type="text" readonly class = "form-control" value = "{{ $gasto->estado != null ? $gasto->estado->nombre : '' }}">
                            
                        </div>
                    </div>

                    
                </div>

                <h3 class="h5-dark"> Usuario Autoriza</h3>
                <div class = "table-responsive">
                        <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                            <tr>
                                <td class="bold">Usuario</td>
                                <td>
                                    <input readonly required type="text" class="form-control" value="{{ $gasto->user_autoriza }}">
                                </td>
                                <td class="bold">Nombre</td>
                                <td>
                                    <input readonly required type="text" class="form-control" value="{{$gasto->usuario_nombre($gasto->user_autoriza)}}">

                                </td>
                                <td class="bold">Fecha Autorización</td>
                                <td>
                                    <input readonly required type="text" class="form-control" value="{{ $gasto->date_autoriza }}">  
                                </td>
                            </tr>
                        </table>
                </div>

                @endisset

                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif

                <a href="{{ route('gastos.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
                <span  style = "display:none;" id = "alert-busqueda">
                    Cargando...
                    <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
                </span>
            </form>

            <!-- AQUI CIERRA EL FORMULARIO  -->

            </div>
          </div>
    </div>
</div>


@endsection



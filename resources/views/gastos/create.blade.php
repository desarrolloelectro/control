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

            <h3 class="h5-dark"> Detalle Usuario</h3>
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
                @if((Auth::user()->validar_permiso('gast_edit_gast') && isset($gasto) && $gasto->estado_id == 1) || !isset($gasto))
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Agencia <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select class="form-control" name="agencia_id" id="agencia_id" aria-required="" aria-required="true" required>
                                <option value="" selected disabled>SELECCIONE</option>
                                @foreach($agencias as $agen)
                                    @if(in_array($agen->codagen,$lista_agencias))
                                    <option value="{{$agen->codagen}}" {{ old('agencia_id', $agencia_id) == $agen->codagen ? 'selected' : ''}}>{{$agen->agennom}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class = "row">
                            <div class = "col-md-8">
                                <div class="form-group">
                                    <label>Tipo Documento <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                                    <select class="form-control" name="tipo_gasto_id" id="tipo_gasto_id" aria-required="" aria-required="true" required>
                                        <option value="" selected disabled>SELECCIONE</option>
                                        @foreach($tipo_gastos as $tipo_gasto)
                                            <?php
                                                $lista_agencias = explode(",",$tipo_gasto->agencias);
                                            ?>
                                            @if(in_array($agencia_id,$lista_agencias))
                                            <option value="{{$tipo_gasto->id}}" {{ old('tipo_gasto_id', $tipo_gasto_id) == $tipo_gasto->id ? 'selected' : ''}}>{{$tipo_gasto->nombre}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class = "col-md-4">
                                <div class="form-group">
                                    <label>No. Documento <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                                    <input required type = "number" name="num_gasto" id="num_gasto" class="form-control" value = "{{ old('num_gasto',$num_gasto) }}">
                                </div>
                            </div>
                        </div>                        
                    </div>

                    
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Área <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select class="form-control" name="area_id" id="area_id" aria-required="" aria-required="true" required>
                                @foreach($areas as $area)
                                    <option value="{{$area->id}}" {{ old('area_id', $area_id) == $area->id ? 'selected' : ''}}>{{$area->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Tipo Identificación <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select class="form-control" name="tipo_identificacion_id" id="tipo_identificacion_id" aria-required="" aria-required="true" required>
                                @foreach($tipo_identificaciones as $tipo_identificacion)
                                    <option value="{{$tipo_identificacion->id}}" {{ old('tipo_identificacion_id', $tipo_identificacion_id) == $tipo_identificacion->id ? 'selected' : ''}}>{{$tipo_identificacion->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>                    
                    <div class = "col-md-4">
                        <div class = "row">
                            <div class = "col-md-8">
                                <div class="form-group">
                                    <label>Identificación <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                                    <input required type = "number" name="identificacion" id="identificacion" class="form-control" value = "{{ old('identificacion',$identificacion) }}">
                                </div>
                            </div>
                            <div class = "col-md-4">
                                <div class="form-group">
                                    <label>DV</label>
                                    <input type = "number" name="dv" id="dv" class="form-control" value = "{{ old('dv',$dv) }}">
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Nombre / Razón Social <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input required type = "text" name="razon" id="razon" class="form-control" value = "{{ old('razon',$razon) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>No. Factura</label>
                            <input type = "number" name="factura" id="factura" class="form-control" value = "{{ old('factura',$factura) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Código Interno / Contrato / Placa</label>
                            <input type = "number" name="codigo" id="codigo" class="form-control" value = "{{ old('codigo',$codigo) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Valor Solicitado <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input required type = "number" name="valor_solicitado" id="valor_solicitado" class="form-control" value = "{{ old('valor_solicitado',$valor_solicitado) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Detalle <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <textarea required name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion',$descripcion) }}</textarea>

                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Tipo Pago</label>
                            <select class="form-control" name="tipo_pago_id" id="tipo_pago_id" required aria-required="true">
                                @foreach($tipo_pagos as $tipo_pago)
                                <option value="{{$tipo_pago->id}}" {{ old('tipo_pago_id',$tipo_pago_id) == $tipo_pago->id ? 'selected' : ''}}>{{$tipo_pago->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id = "mostrar_bancos" class = "col-md-8" style ="display:none;">
                        <div class="form-group">
                            <label>Banco</label>
                            <select class="form-control" name="banco_id" id="banco_id" required aria-required="true">
                                <option value="0">NO DEFINE</option>
                                @foreach($bancos as $banco)
                                <option value="{{$banco->id}}" {{ old('banco_id',$banco_id) == $banco->id ? 'selected' : ''}}>{{$banco->nombre}} :: {{$banco->num_cuenta}} :: {{$banco->tipo_pago != null ? $banco->tipo_pago->nombre : ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                </div>


                @else


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
                @endif

                

                @if((Auth::user()->validar_permiso('gast_cargar_cot') && isset($gasto) && $gasto->estado_id == 1) || !isset($gasto))
                <h3 class="h5-dark"> Cargar Autorización / Cotización <i style = 'color:red;font-size:12px;' class="fa fa-asterisk"></i></h3>

                <div class = "row">
                    <div class = "col-md-6">
                            <button type="button" id = "btn_cargar_cotizacion" class="btn btn-warning"  data-toggle="modal" data-target="#modalCotizacion" >Cargar Autorización / Cotización</button>
                    </div>
                </div>
                <br>
                @else
                <h3 class="h5-dark"> Cotización Cargada</h3>

                @endif
                
                
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
                
                @if((Auth::user()->validar_permiso('gast_adjuntar') && isset($gasto) && $gasto->estado_id == 1) || !isset($gasto))
                <h3 class="h5-dark"> Adjuntar Archivos</h3>

                <div class = "row">
                    <div class = "col-md-6">
                            <button type="button" id = "btn-agregar" class="btn btn-info" onclick = "agregar();">Agregar Fila</button>
                    </div>
                </div>
                <br>
                @else
                <h3 class="h5-dark"> Archivos Adjuntos</h3>
                @endif
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
                                        @if(Auth::user()->validar_permiso('gast_edit_archivos') && $gasto->estado_id == 1)
                                        <td> 
                                            <input type="hidden" name = "id_tabla[]" id = "id_tabla[]" value = "{{ $detalle->id }}">
                                            <input required type="text" class = "form-control" name = "nombre_tabla[]" id = "nombre_tabla[]" value = "{{ $detalle->nombre }}">
                                        </td>
                                        <td>
                                            <input style = "padding: 0.15rem 0.15rem;" type="file" class = "form-control" name = "archivo_tabla[]" id = "archivo_tabla[]" value = "">
                                        </td>
                                        <td>
                                            <center style = "padding-top:7px!important">
                                            <a class="btn2 btn-info" target = "_blank" href="{{ asset('uploads/gastos') }}/{{$detalle->urlarchivo}}" class = "bold"><i class = "fa fa-search-plus"></i></a>
                                            @if(Auth::user()->validar_permiso('gast_eliminar_archivos') && $gasto->estado_id == 1 )
                                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar_gasto({{$detalle->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                                            @endif
                                            </center>
                                        </td>
                                        @else
                                        <td> 
                                            <input required readonly type="text" class = "form-control" value = "{{ $detalle->nombre }}">
                                        </td>
                                        <td>
                                            <input readonly style = "padding: 0.15rem 0.15rem;" type="text" class = "form-control" value = "Archivo Seleccionado">
                                        </td>
                                        <td>
                                            <center style = "padding-top:7px!important">
                                            <a class="btn2 btn-info" target = "_blank" href="{{ asset('uploads/gastos') }}/{{$detalle->urlarchivo}}" class = "bold"><i class = "fa fa-search-plus"></i></a>
                                            @if(Auth::user()->validar_permiso('gast_eliminar_archivos') && $gasto->estado_id == 1 )
                                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar_gasto({{$detalle->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                                            @endif
                                             </center>
                                        </td>
                                        @endif

                                        
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
                @if(Auth::user()->validar_permiso('gast_obs_auditoria') && $gasto->estado_id == 1 )
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Observaciones Auditoría</label>
                            <textarea name="obs_auditoria" id="obs_auditoria" rows="3" class="form-control">{{ old('obs_auditoria',$obs_auditoria) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Tipo Documento</label>
                            <select class="form-control" name="tipo_doc_audi" id="tipo_doc_audi" aria-required="" aria-required="true" required>
                                <option value="0" selected>NO DEFINE</option>
                                @foreach($tipo_gastos as $tipo_gasto)
                                    <?php
                                        $lista_agencias = explode(",",$tipo_gasto->agencias);
                                    ?>
                                    @if(in_array($agencia_id,$lista_agencias))
                                    <option value="{{$tipo_gasto->id}}" {{ old('tipo_doc_audi', $tipo_doc_audi) == $tipo_gasto->id ? 'selected' : ''}}>{{$tipo_gasto->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>No. Documento</label>
                            <input type = "text" name="num_doc_audi" id="num_doc_audi" class="form-control" value = "{{ old('num_doc_audi',$num_doc_audi) }}">
                        </div>
                    </div>
                </div>
                @else
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
                @endif

                @if(Auth::user()->validar_permiso('gast_obs_revisoria'))
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Observaciones Revisoría</label>
                            <textarea name="obs_revisoria" id="obs_revisoria" rows="3" class="form-control">{{ old('obs_revisoria',$obs_revisoria) }}</textarea>
                        </div>
                    </div>
                </div>
                @else
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Observaciones Revisoría</label>
                            <textarea readonly rows="3" class="form-control">{{ old('obs_revisoria',$obs_revisoria) }}</textarea>
                        </div>
                    </div>
                </div>
                @endif                               
                
                <div class = "row">
                    @if(Auth::user()->validar_permiso('gast_valor') && $gasto->estado_id == 1)
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Valor Autorizado</label>
                            <input type = "number" name="valor_autorizado" id="valor_autorizado" class="form-control" value = "{{ old('valor_autorizado',$valor_autorizado) }}">
                        </div>
                    </div>
                    @else
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Valor Autorizado</label>
                            <input type = "number" readonly class="form-control" value = "{{ old('valor_autorizado',$valor_autorizado) }}">
                        </div>
                    </div>
                    @endif

                    @if((Auth::user()->validar_permiso('gast_estados') && $gasto->estado_id == 1) || Auth::user()->validar_permiso('gast_revertir'))
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Estado <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select class="form-control" name="estado_id" id="estado_id" aria-required="" aria-required="true" required>
                                @foreach($estados as $estado)
                                    <option value="{{$estado->id}}" {{ old('estado_id', $estado_id) == $estado->id ? 'selected' : ''}}>{{$estado->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Estado <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input type="text" readonly class = "form-control" value = "{{ $gasto->estado != null ? $gasto->estado->nombre : '' }}">
                            
                        </div>
                    </div>
                    @endif

                    
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
                
                @isset($gasto)
                    @if(Auth::user()->validar_permiso('gast_btn_actualizar'))
                    <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                    @endif
                @else
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                @endisset

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


  <!-- Modal -->
  <div class="modal fade" id="modalCotizacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Autorizaciones / Cotizaciones</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class = "contenido-modal">
                    Cargando...
                    <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  


<script type="text/javascript">
    jQuery(function($) {

        $("#formulario").submit(function(event){
            id_cotizacion = $("#id_cotizacion").val();
            existe_gasto = "{{isset($gasto)}}";           
            if(id_cotizacion == '' && existe_gasto == 0){
                alert("El campo Autorización / Cotización es requerido");
                event.preventDefault();
            }else{
                $("#alert-busqueda").show();
            }
        })

        $(document).on('click', '.btn-delete', function (event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });

        $("#agencia_id").change(function(){
            var agencia_id = $(this).val();
            $.ajax({
                url: "{{ route('gastos.cargar_tipo_gastos') }}",
                method: 'POST',
                data: {agencia_id:agencia_id, "_token": "{{ csrf_token() }}"},
                success: function(data) {
                    $("#tipo_gasto_id").html('');
                    $("#tipo_gasto_id").html(data.options);
                    $('#tipo_gasto_id').val(null).trigger('change');

                    $("#tipo_doc_audi").html('');
                    $("#tipo_doc_audi").html(data.options);
                    $('#tipo_doc_audi').val(null).trigger('change');
                }
            });
        });

        $("#tipo_pago_id").change(function(){
            var tipo_pago_id = $(this).val();
            $.ajax({
                url: "{{ route('bancos.cargar_bancos') }}",
                method: 'POST',
                data: {tipo_pago_id:tipo_pago_id, "_token": "{{ csrf_token() }}"},
                success: function(data) {
                    $("#banco_id").html('');
                    $("#banco_id").html(data.options);
                    $('#banco_id').val('0').trigger('change');
                }
            });
        });

        $("#btn_cargar_cotizacion").click(function(){
            agencia_id = $("#agencia_id").val();
            if(agencia_id === null || agencia_id == ''){
                $("#modalCotizacion .modal-body .contenido-modal").html('');
                $("#modalCotizacion .modal-body .contenido-modal").html('¡Debe seleccionar una agencia!');
            }else{
                $.ajax({
                    url: "{{ route('cotizaciones.cargar_cotizaciones') }}",
                    method: 'POST',
                    data: {"agencia_id":agencia_id,"_token": "{{ csrf_token() }}"},
                    success: function(data) {
                        $("#modalCotizacion .modal-body .contenido-modal").html('');
                        $("#modalCotizacion .modal-body .contenido-modal").html(data.options);
                    }
                });
            }
            
        });

        $("#agencia_id").select2();
        $("#tipo_gasto_id").select2();
        $("#tipo_doc_audi").select2();
        $("#area_id").select2();

        $("#estado_id").change(function(){
            estado = $("#estado_id").val();
            if(estado == 2){
                $("#valor_autorizado").prop('required',true);
            }else{
                $("#valor_autorizado").prop('required',false);
            }
        });

        $("#tipo_pago_id").change(function(){
            tipo_pago_id = $("#tipo_pago_id").val();
            if(tipo_pago_id != '1'){
                $("#mostrar_bancos").show();
            }else{
                $("#mostrar_bancos").hide();
            }
        });


        tipo_pago_id = $("#tipo_pago_id").val();
        if(tipo_pago_id != '1'){
            $("#mostrar_bancos").show();
        }else{
            $("#mostrar_bancos").hide();
        }



    });

    function agregar(){

        cadena = "<tr>";
        cadena += "<td><input class = 'form-control' required type='text' name='nombre[]' value='' /></td>";
        cadena += "<td><input style = 'padding: 0.15rem 0.15rem;' class = 'form-control' required type='file' name='archivo[]' value='' /></td>";
        cadena +=  "<td><button type='button' class = 'btn-delete' ><i class='fa fa-times-circle red bigger-24' aria-hidden='true'></i></button></td>";
        cadena += "</tr>";

        $("#tabla_agregar tbody").append(cadena);
    }

    function eliminar_gasto(id,event){
        event.preventDefault();
        bootbox.confirm({
            message: "Está seguro que desea eliminar el registro?",
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirmar',
                    className: 'btn-success'
                },
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancelar',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result == true){

                    //eliminar gasto
                    $("#alert-busqueda").show();
                    $.ajax({
                    url: "{{ route('gastos.eliminar_gasto') }}",
                    method: 'POST',
                    data: {id:id, "_token": "{{ csrf_token() }}"},
                    success: function(data) {
                        if(data.status == 'success'){
                            location.reload();
                        }else{
                            $("#alert-error").html(data.mensaje);
                            $("#alert-error").show();
                        }
                    }
                });

                }
            }
        });
    }

    function agregar_cotizacion(id,event){
        event.preventDefault();

        $.ajax({
            url: "{{ route('cotizaciones.buscar') }}",
            method: 'POST',
            data: {'id':id,"_token": "{{ csrf_token() }}"},
            beforeSend: function() {
            },
            success: function(data) {

                $("#id_cotizacion").val(id);
                $("#id_cotizacion_nombre").val(id);
                $("#descripcion_cotizacion").val(data.descripcion);
                $("#agencia_cotizacion").val(data.agencia);
                $("#tipo_gasto_cotizacion").val(data.tipo_gasto);
                $("#estado_cotizacion").val(data.estado);
                $("#valor_cotizacion").val(data.valor);
                $("#fecha_cotizacion").val(data.fecha);

                $("#valor_autorizado").val(data.valor);

                
            }
        });

        $("#modalCotizacion").modal("toggle");
    }

    

</script>
@endsection



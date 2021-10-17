@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($cotizacion)){
    $nombre = $cotizacion->nombre;
    $agencia_id = $cotizacion->agencia_id;
    $estado_id = $cotizacion->estado_id;
    $tipo_gasto_id = $cotizacion->tipo_gasto_id;
    $nombre = $cotizacion->nombre;
    $descripcion = $cotizacion->descripcion;
    $fecha_sistema = $cotizacion->date_new;
    $obs = $cotizacion->obs;

    $tipo_gasto_id_gasto = $cotizacion->tipo_gasto_id_gasto;
    $num_gasto = $cotizacion->num_gasto;
    $area_id = $cotizacion->area_id;
    $factura = $cotizacion->factura;
    $codigo = $cotizacion->codigo;
    $valor_egreso = $cotizacion->valor_egreso;
    $valor_autorizado = $cotizacion->valor_autorizado;
    $gasto_estado_id = $cotizacion->gasto_estado_id;
    $revisoria_estado_id = $cotizacion->revisoria_estado_id;

    $descripcion_gasto = $cotizacion->descripcion_gasto;
    $tipo_pago_id = $cotizacion->tipo_pago_id;
    $banco_id = $cotizacion->banco_id;
    $obs_auditoria = $cotizacion->obs_auditoria;
    $obs_revisoria = $cotizacion->obs_revisoria;
    $user_autoriza_gasto = $cotizacion->user_autoriza_gasto;
    $date_autoriza_gasto = $cotizacion->date_autoriza_gasto;

    $bloq_coti = false;
    if($cotizacion->estado_id == 2){
        $bloq_coti = true;
    }

    $bloq_gasto = false;
    if($cotizacion->gasto_estado_id == 2){
        $bloq_gasto = true;
    }





}else{
    $nombre ="";
    $agencia_id ="";
    $estado_id ="";
    $tipo_gasto_id ="";
    $nombre ="";
    $descripcion ="";
    $obs ="";

    $num_gasto = "";
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
    $valor_egreso ="";
    $valor_autorizado ="";  
    
    $banco_id ="";    
    $tipo_pago_id ="";    
    $tipo_doc_audi ="";    
    $num_doc_audi ="";   
    $descripcion_gasto = "";
    $gasto_estado_id = "";
    $revisoria_estado_id = "";
    $tipo_gasto_id_gasto ="";

    $bloq_coti = false;
    $bloq_gasto = false;





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
                                <input readonly required type="text" class="form-control" value="{{ $usuario != null ? $usuario->nombre : '' }}">
                            </td>
                            <td class="bold">Celular</td>
                            <td>
                                <input readonly required type="text" class="form-control" value="{{  $usuario != null ? $usuario->telefono : '' }}">

                            </td>
                            <td class="bold">Doc. Vinculado</td>
                            <td>
                                <input readonly required type="text" class="form-control" value="{{  $usuario != null ? $usuario->cedula : ''}}">  
                            </td>
                        </tr>
                        <tr>
                            <td class="bold">Fecha</td>
                            <td>
                                <input readonly required type="text" class="form-control" value="{{ $fecha_sistema }}">
                            </td>
                            <td class="bold">Agencia</td>
                            <td>
                                <input readonly required type="text" class="form-control" value="{{ $usuario != null ? $usuario->agencia_detalle($usuario->agencia) : ''}}">
                            </td>
                            <td>
                            @if(2==1)
                            @isset($cotizacion)
                                <a href="{{ route('cotizaciones.enviar', ['id'=>$cotizacion->id]) }}" class="btn btn-danger btn-enviar"><i class="fa fa-share-square-o" aria-hidden="true"></i>Enviar Cotización</a>
                            @endisset
                            @endif
                            </td>
                            <td>
                            <span style="display:none;" id="alert-enviar">
                                Enviando...
                                <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
                            </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @if(session()->has('mail'))
                    <div class="alert alert-success">
                        {{ session()->get('mail') }}
                    </div>
            @endif
            @if(session()->has('alerta'))
                    <div class="alert alert-danger">
                        {{ session()->get('alerta') }}
                    </div>
            @endif
            

            <!-- AQUI INICIA EL FORMULARIO  -->
            <form method="POST" id = "formulario" action="{{ $accion }}" files="true" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ $metodo }}
               
                <h3 class="h5-dark"> Detalle Autorización / Cotización</h3>
                <div class = "row">
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>Agencia <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select <?= $bloq_coti ? 'disabled' : ''  ?> class="form-control" name="agencia_id" id="agencia_id" aria-required="" aria-required="true" required>
                                <option value="" selected disabled>SELECCIONE</option>
                                @foreach($agencias as $agen)
                                    @if(in_array($agen->codagen,$lista_agencias))
                                    <option value="{{$agen->codagen}}" {{ old('agencia_id', $agencia_id) == $agen->codagen ? 'selected' : ''}}>{{$agen->agennom}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>Tipo Gasto <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select  <?= $bloq_coti ? 'disabled' : ''  ?> class="form-control" name="tipo_gasto_id" id="tipo_gasto_id" aria-required="" aria-required="true" required>
                                <option value="" selected disabled>SELECCIONE</option>
                                @foreach($tipo_gastos as $tipo_gasto)
                                    <?php
                                        $lista_agencias = explode(",",$tipo_gasto->agencias);
                                    ?>
                                    @if(in_array($agencia_id,$lista_agencias))
                                    <option value="{{$tipo_gasto->id}}" {{ old('tipo_gasto_id', $tipo_gasto_id) == $tipo_gasto->id ? 'selected' : ''}} >{{$tipo_gasto->tipo}} :: {{$tipo_gasto->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Detalle <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <textarea  <?= $bloq_coti ? 'readonly' : ''  ?> required name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion',$descripcion) }}</textarea>

                        </div>
                    </div>
                </div>
                @if(Auth::user()->validar_permiso('cot_adjuntar') && !$bloq_coti)
                <h3 class="h5-dark"> Adjuntar Cotizaciones / Autorizaciones</h3>
                <div class = "row">
                    <div class = "col-md-6">
                            <button type="button" id = "btn-agregar" class="btn btn-info" onclick = "agregar();">Agregar Fila</button>
                    </div>
                </div>
                <br>
                @else
                <h3 class="h5-dark"> Cotizaciones / Autorizaciones Adjuntas</h3>
                @endif
                

                <div class="row">
                    <div class="col-md-12">
                    <div class="table-responsive">
                        <table id = "tabla_agregar" class="table table-list table-striped table-bordered">
                            <thead style = "font-weight:bold!important;">
                                <tr>
                                    <th>NOMBRE ARCHIVO</th>
                                    <th>VALOR COTIZACIÓN</th>
                                    <th>ARCHIVO</th>
                                    <th style = "width:50px;">ACCIONES</th>
                                    @isset($cotizacion_detalle)
                                    <th><center>AUTORIZAR</center></th>
                                    @endisset
                                </tr>
                            </thead> 
                        <tbody>

                            @isset($cotizacion_detalle)
                                @if ($cotizacion_detalle->isNotEmpty())
                                    <?php
                                    $edit_fcoti = false;
                                    if(Auth::user()->validar_permiso('cot_edit_archivos')){
                                        $edit_fcoti = true;
                                    }
                                    ?>
                                    @foreach($cotizacion_detalle as $index => $detalle)
                                    <tr>

                                        <td> 
                                            <input type="hidden" name = "id_tabla[]" id = "id_tabla[]" value = "{{ $detalle->id }}">
                                            <input <?php if(!$edit_fcoti || $bloq_coti) echo 'readonly'; ?> required type="text" class = "form-control" name = "nombre_tabla[]" id = "nombre_tabla[]" value = "{{ $detalle->nombre }}">
                                        </td>
                                        <td>
                                            <input  <?php if(!$edit_fcoti || $bloq_coti) echo 'readonly'; ?> required type="number" class = "form-control" name = "valor_tabla[]" id = "valor_tabla[]" value = "{{ $detalle->valor }}">
                                        </td>
                                        <td>
                                            <input <?php if(!$edit_fcoti || $bloq_coti) echo 'disabled'; ?> style = "padding: 0.15rem 0.15rem;" type="file" class = "form-control" name = "archivo_tabla[]" id = "archivo_tabla[]" value = "">
                                        </td>
                                        <td>
                                            <center style = "padding-top:7px!important">
                                            <a class="btn2 btn-info" target = '_blank' href="{{ asset('uploads/cotizaciones') }}/{{$detalle->urlarchivo}}" class = "bold"><i class = "fa fa-search-plus"></i></a>
                                            @if(Auth::user()->validar_permiso('cot_eliminar_archivos')  && $cotizacion->estado_id == 1 )
                                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar_cotizacion({{$detalle->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                                            @endif
                                            </center>
                                        </td>                                        

                                        @if(Auth::user()->validar_permiso('cot_autorizar') && $cotizacion->estado_id == 1 )
                                        <td>
                                            <center style = "padding-top:7px!important">
                                                    <input type="radio" id="autorizado_tabla" name="autorizado_tabla" value="{{$detalle->id}}" <?php if($detalle->autorizado == 1) echo 'checked'; ?> >
                                            </center>
                                        </td>
                                        @else
                                        <td>
                                            <center>
                                                    <span><?php if($detalle->autorizado == 1) echo "<i style = 'font-size:20px;' class = 'fa fa-check-circle-o'></i>"; ?></span>
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
                @isset($cotizacion)
                    <div class = "row">
                        <div class = "col-md-12">
                            <div class="form-group">
                                <label>Observaciones Auditoría</label>
                                <?php
                                $edit_cot_obs = false;
                                if(Auth::user()->validar_permiso('cot_obs')){
                                    $edit_cot_obs = true;
                                }
                                ?>
                                <textarea <?php if(!$edit_cot_obs || $bloq_coti) echo 'readonly';?> name="obs" id="obs" rows="3" class="form-control">{{ old('obs',$obs) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class = "row">
                        <div class = "col-md-4">
                            <div class="form-group">
                                <label>Estado Autorización / Cotización<span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                                @if((Auth::user()->validar_permiso('cot_estados') && $cotizacion->estado_id != 2) || Auth::user()->validar_permiso('cot_revertir') )
                                <select class="form-control" name="estado_id" id="estado_id" aria-required="" aria-required="true" required>
                                    @foreach($estados as $estado)
                                        <option value="{{$estado->id}}" {{ old('estado_id', $estado_id) == $estado->id ? 'selected' : ''}}>{{$estado->nombre}}</option>
                                    @endforeach
                                </select>
                                @else
                                <input type="text" class = 'form-control' readonly value = "{{$cotizacion->estado != null ? $cotizacion->estado->nombre : 'PENDIENTE'}}">
                                @endif
                            </div>
                        </div>                        
                    </div>

                    <h3 class="h5-dark"> Usuario Autoriza</h3>
                    <div class = "table-responsive">
                        <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                            <tr>
                                <td class="bold">Usuario</td>
                                <td>
                                    <input readonly required type="text" class="form-control" value="{{ $cotizacion->user_autoriza }}">
                                </td>
                                <td class="bold">Nombre</td>
                                <td>
                                    <input readonly required type="text" class="form-control" value="{{$cotizacion->usuario_nombre($cotizacion->user_autoriza)}}">

                                </td>
                                <td class="bold">Fecha Autorización</td>
                                <td>
                                    <input readonly required type="text" class="form-control" value="{{ $cotizacion->date_autoriza }}">  
                                </td>
                            </tr>
                        </table>
                    </div>                
                @endisset

                @if(isset($cotizacion) && $cotizacion->estado_id == 2 && Auth::user()->validar_permiso('cot_creat_gast'))

                <h3 class="h5-blue"> Gasto</h3>
                <div class = 'row'>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Tipo Documento <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select <?= $bloq_gasto ? 'disabled' : ''  ?> class="form-control" name="tipo_gasto_id_gasto" id="tipo_gasto_id_gasto" aria-required="" aria-required="true" required>
                                <option value="" selected disabled>SELECCIONE</option>
                                @foreach($tipo_gastos as $tipo_gasto)
                                    <?php
                                        $lista_agencias = explode(",",$tipo_gasto->agencias);
                                    ?>
                                    @if(in_array($agencia_id,$lista_agencias))
                                    <option value="{{$tipo_gasto->id}}" {{ old('tipo_gasto_id_gasto', $tipo_gasto_id_gasto) == $tipo_gasto->id ? 'selected' : ''}}>{{$tipo_gasto->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>No. Documento <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input  <?= $bloq_gasto ? 'readonly' : ''  ?> required type = "number" name="num_gasto" id="num_gasto" class="form-control" value = "{{ old('num_gasto',$num_gasto) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Área <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select  <?= $bloq_gasto ? 'disabled' : ''  ?> class="form-control" name="area_id" id="area_id" aria-required="" aria-required="true" required>
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
                            <label>No. Factura</label>
                            <input  <?= $bloq_gasto ? 'readonly' : ''  ?> type = "number" name="factura" id="factura" class="form-control" value = "{{ old('factura',$factura) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Código Interno / Contrato / Placa</label>
                            <input  <?= $bloq_gasto ? 'readonly' : ''  ?> type = "text" name="codigo" id="codigo" class="form-control" value = "{{ old('codigo',$codigo) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Valor Egreso <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            @if($bloq_gasto)
                                @if(is_numeric($valor_egreso))
                                <input type="hidden" name="valor_egreso" id="valor_egreso" value = "{{ old('valor_egreso',$valor_egreso) }}">
                                <input  readonly type = "text" class="form-control" value = "$ {{number_format($valor_egreso, 1, ',', '.')}}">
                                @else
                                <input  required type = "number" name="valor_egreso" id="valor_egreso" class="form-control" value = "{{ old('valor_egreso',$valor_egreso) }}">
                                @endif
                            @else
                            <input  required type = "number" name="valor_egreso" id="valor_egreso" class="form-control" value = "{{ old('valor_egreso',$valor_egreso) }}">
                            @endif
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Detalle <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <textarea  <?= $bloq_gasto ? 'readonly' : ''  ?> required name="descripcion_gasto" id="descripcion_gasto" rows="3" class="form-control">{{ old('descripcion_gasto',$descripcion_gasto) }}</textarea>

                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Tipo Pago</label>
                            <select  <?= $bloq_gasto ? 'disabled' : ''  ?> class="form-control" name="tipo_pago_id" id="tipo_pago_id" required aria-required="true">
                                @foreach($tipo_pagos as $tipo_pago)
                                <option value="{{$tipo_pago->id}}" {{ old('tipo_pago_id',$tipo_pago_id) == $tipo_pago->id ? 'selected' : ''}}>{{$tipo_pago->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id = "mostrar_bancos" class = "col-md-8" style ="display:none;">
                        <div class="form-group">
                            <label>Banco</label>
                            <select  <?= $bloq_gasto ? 'disabled' : ''  ?> class="form-control" name="banco_id" id="banco_id">
                                <option value="0">NO DEFINE</option>
                                @foreach($bancos as $banco)
                                <option value="{{$banco->id}}" {{ old('banco_id',$banco_id) == $banco->id ? 'selected' : ''}}>{{$banco->nombre}} :: {{$banco->num_cuenta}} :: {{$banco->tipo_pago != null ? $banco->tipo_pago->nombre : ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->validar_permiso('cot_files_gast') && !$bloq_gasto)
                <h3 class="h5-blue">Adjuntar Soportes</h3>
                <div class = "row">
                    <div class = "col-md-6">
                            <button type="button" class="btn btn-info" onclick = "agregar_soportes();">Agregar Fila</button>
                    </div>
                </div>
                <br>
                @else
                <h3 class="h5-blue">Soportes Adjuntos</h3>
                @endif
                <div class="row">
                    <div class="col-md-12">
                    <div class="table-responsive">
                        <table id = "tabla_agregar_soportes" class="table table-list table-striped table-bordered">
                            <thead style = "font-weight:bold!important;">
                                <tr>
                                    <th>DESCRIPCIÓN ARCHIVO</th>
                                    <th style = "width:400px;">ARCHIVO</th>
                                    <th style = "width:50px;">ACCIONES</th>
                                </tr>
                            </thead> 
                        <tbody>

                            @isset($cotizacion_soporte)
                                @if ($cotizacion_soporte->isNotEmpty())
                                    <?php
                                    $edit_files = false;
                                    if(Auth::user()->validar_permiso('cot_edit_files_gast')){
                                        $edit_files = true;
                                    }
                                    ?>
                                    @foreach($cotizacion_soporte as $index => $detalle)
                                    <tr>
                                        <td> 
                                            <input type="hidden" name = "id_sop_tabla[]" id = "id_sop_tabla[]" value = "{{ $detalle->id }}">
                                            <input required <?php if(!$edit_files || $bloq_gasto) echo 'readonly'?> type="text" class = "form-control" name = "nombre_sop_tabla[]" id = "nombre_sop_tabla[]" value = "{{ $detalle->nombre }}">
                                        </td>
                                        <td>
                                            <input <?php if(!$edit_files || $bloq_gasto) echo 'disabled'?> style = "padding: 0.15rem 0.15rem;" type="file" class = "form-control" name = "archivo_sop_tabla[]" id = "archivo_sop_tabla[]" value = "">
                                        </td>
                                        <td>
                                            <center style = "padding-top:7px!important">
                                            <a class="btn2 btn-info" target = "_blank" href="{{ asset('uploads/gastos') }}/{{$detalle->urlarchivo}}" class = "bold"><i class = "fa fa-search-plus"></i></a>
                                            @if(Auth::user()->validar_permiso('cot_del_files_gast') && !$bloq_gasto)
                                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar_gasto({{$detalle->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                                            @endif
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

                <h3 class="h5-blue"> Auditoría y Revisoría</h3>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Observaciones Auditoría</label>
                            <?php
                            $edit_obs_aud = false;
                            if(Auth::user()->validar_permiso('cto_obs_aud_gast')){
                                $edit_obs_aud = true;
                            }
                            ?>
                            <textarea <?php if(!$edit_obs_aud || $bloq_gasto)  echo 'readonly'; ?> name="obs_auditoria" id="obs_auditoria" rows="3" class="form-control">{{ old('obs_auditoria',$obs_auditoria) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Observaciones Revisoría</label>
                            <?php
                            $edit_obs_rev = false;
                            if(Auth::user()->validar_permiso('cot_obs_rev_gast')){
                                $edit_obs_rev = true;
                            }
                            ?>
                            <textarea <?php if(!$edit_obs_rev)  echo 'readonly'; ?> name="obs_revisoria" id="obs_revisoria" rows="3" class="form-control">{{ old('obs_revisoria',$obs_revisoria) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Valor Autorizado <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <?php
                            $edit_valor = false;
                            if(Auth::user()->validar_permiso('cot_valor_gast')){
                                $edit_valor = true;
                            }
                            ?>

                            @if(!$edit_valor || $bloq_gasto)
                                @if(is_numeric($valor_autorizado))
                                <input type="hidden" name="valor_autorizado" id="valor_autorizado" value = "{{ old('valor_autorizado',$valor_autorizado) }}">
                                <input  readonly type = "text" class="form-control" value = "$ {{number_format($valor_autorizado, 1, ',', '.')}}">
                                @else
                                <input required type = "number" name="valor_autorizado" id="valor_autorizado" class="form-control" value = "{{ old('valor_autorizado',$valor_autorizado) }}">
                                @endif
                            @else
                            <input required type = "number" name="valor_autorizado" id="valor_autorizado" class="form-control" value = "{{ old('valor_autorizado',$valor_autorizado) }}">
                            @endif


                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Estado Gasto<span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            @if((Auth::user()->validar_permiso('cot_estados_gast') && $cotizacion->gasto_estado_id != 2) || Auth::user()->validar_permiso('cot_revertir_gast') )
                            <select class="form-control" name="gasto_estado_id" id="gasto_estado_id" aria-required="" aria-required="true" required>
                                @foreach($gasto_estados as $estado)
                                    <option value="{{$estado->id}}" {{ old('gasto_estado_id', $gasto_estado_id) == $estado->id ? 'selected' : ''}}>{{$estado->nombre}}</option>
                                @endforeach
                            </select>
                            @else
                            <input readonly type="text" class = 'form-control' value = "{{$cotizacion->gasto_estado != null ? $cotizacion->gasto_estado->nombre : 'PENDIENTE'}}">
                            @endif
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Estado Revisoría</label>
                            <select class="form-control" name="revisoria_estado_id" id="revisoria_estado_id" aria-required="" aria-required="true" required>
                                @foreach($revisoria_estados as $estado)
                                    <option value="{{$estado->id}}" {{ old('revisoria_estado_id', $revisoria_estado_id) == $estado->id ? 'selected' : ''}}>{{$estado->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    
                </div>
                <h3 class="h5-blue"> Usuario Autoriza</h3>
                <div class = "table-responsive">
                        <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                            <tr>
                                <td class="bold">Usuario</td>
                                <td>
                                    <input readonly required type="text" class="form-control" value="{{ $cotizacion->user_autoriza_gasto }}">
                                </td>
                                <td class="bold">Nombre</td>
                                <td>
                                    <input readonly required type="text" class="form-control" value="{{$cotizacion->usuario_nombre($cotizacion->user_autoriza_gasto)}}">

                                </td>
                                <td class="bold">Fecha Autorización</td>
                                <td>
                                    <input readonly required type="text" class="form-control" value="{{ $cotizacion->date_autoriza_gasto }}">  
                                </td>
                            </tr>
                        </table>
                </div>
                @endif

                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif

                @isset($cotizacion)
                    @if(Auth::user()->validar_permiso('cot_btn_actualizar'))
                    <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                    @endif
                @else
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                @endisset
               
                <a href="{{ route('cotizaciones.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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
<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Imagen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                            
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
    jQuery(function($) {
        $("#formulario").submit(function(){
            $("#alert-busqueda").show();
        })

        $(".btn-enviar").click(function(){
            $("#alert-enviar").show();
        })

        $(document).on('click', '.btn-delete', function (event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });

        $("#agencia_id").change(function(){
            var agencia_id = $(this).val();
            $.ajax({
                url: "{{ route('cotizaciones.cargar_tipo_gastos') }}",
                method: 'POST',
                data: {agencia_id:agencia_id, "_token": "{{ csrf_token() }}"},
                success: function(data) {
                    $("#tipo_gasto_id").html('');
                    $("#tipo_gasto_id").html(data.options);
                    $('#tipo_gasto_id').val(null).trigger('change');

                    $("#tipo_gasto_id_gasto").html('');
                    $("#tipo_gasto_id_gasto").html(data.options);
                    $('#tipo_gasto_id_gasto').val(null).trigger('change');
                }
            });
        });

        $("#agencia_id").select2();
        $("#tipo_gasto_id").select2();
        $("#tipo_gasto_id_gasto").select2();

        $("#estado_id").change(function(){
            estado = $("#estado_id").val();
            if(estado == 2){
                $("#autorizado_tabla").prop('required',true);
            }else{
                $("#autorizado_tabla").prop('required',false);
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
        banco_id = "{{$banco_id}}";
        if(tipo_pago_id != '1'){
            $("#mostrar_bancos").show();
        }else{
            $("#mostrar_bancos").hide();
        }
        $.ajax({
            url: "{{ route('bancos.cargar_bancos') }}",
            method: 'POST',
            data: {tipo_pago_id:tipo_pago_id, "_token": "{{ csrf_token() }}"},
            success: function(data) {
                $("#banco_id").html('');
                $("#banco_id").html(data.options);
                $('#banco_id').val(banco_id).trigger('change');
            }
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


    });

    function abrirImagen(url,event){
        event.preventDefault();
        $("#modalImagen .modal-body").html("<img id = 'imagen_girar' style = 'width:100%;' src='"+url+"'>");
        $("#modalImagen").modal();
        
    }

    function agregar(){

        cadena = "<tr>";
        cadena += "<td><input class = 'form-control' required type='text' name='nombre[]' value='' /></td>";
        cadena += "<td><input class = 'form-control' required type='number' name='valor[]' value='' /></td>";
        cadena += "<td><input style = 'padding: 0.15rem 0.15rem;'  class = 'form-control' required type='file' name='archivo[]' value='' /></td>";
        cadena +=  "<td><button type='button' class = ' btn3 btn-danger btn-delete' ><i class='fa fa-times-circle red bigger-24' aria-hidden='true'></i></button></td>";
        cadena += "</tr>";

        $("#tabla_agregar tbody").append(cadena);
    }

    function agregar_soportes(){

        cadena = "<tr>";
        cadena += "<td><input class = 'form-control' required type='text' name='nombre_sop[]' value='' /></td>";
        cadena += "<td><input style = 'padding: 0.15rem 0.15rem;' class = 'form-control' required type='file' name='archivo_sop[]' value='' /></td>";
        cadena +=  "<td><button type='button' class = 'btn3 btn-danger btn-delete' ><i class='fa fa-times-circle red bigger-24' aria-hidden='true'></i></button></td>";
        cadena += "</tr>";

        $("#tabla_agregar_soportes tbody").append(cadena);
    }

    function eliminar_cotizacion(id,event){
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

                    //eliminar cotizacion
                    $("#alert-busqueda").show();
                    $.ajax({
                    url: "{{ route('cotizaciones.eliminar_cotizacion') }}",
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
                    url: "{{ route('cotizaciones.eliminar_gasto') }}",
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

    

</script>
@endsection



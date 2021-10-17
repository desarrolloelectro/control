@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($dia_iva)){
    $tipoid = $dia_iva->tipoid;
    $identificacion = $dia_iva->identificacion;
    $nombre = $dia_iva->nombre;
    $tipofac = $dia_iva->tipofac;
    $tipodoc = $dia_iva->tipodoc;
    $numdoc = $dia_iva->numdoc;
    $fecha = $dia_iva->fecha;
    $categoria = $dia_iva->categoria;
    $genero = $dia_iva->genero;
    $cantidad = $dia_iva->cantidad;
    $unidad = $dia_iva->unidad;
    $descripcion = $dia_iva->descripcion;
    $vrunit = $dia_iva->vrunit;
    $vrtotal = $dia_iva->vrtotal;
    $mediopago = $dia_iva->mediopago;
    $numsoporte = $dia_iva->numsoporte;
    $fechaentrega = $dia_iva->fechaentrega;
    $pvppublico = $dia_iva->pvppublico;
    $urlimagen = $dia_iva->urlimagen;
    $obs = $dia_iva->obs;
    $estado_id = $dia_iva->estado_id;
    $banco_estado_id = $dia_iva->banco_estado_id;
    $caja2_estado_id = $dia_iva->caja2_estado_id;
    $lugar = $dia_iva->tipo_documento != null ? $dia_iva->tipo_documento->codciu." :: ".$dia_iva->tipo_documento->ciudad." :: ".$dia_iva->tipo_documento->coddpto." :: ".$dia_iva->tipo_documento->depto :'NO DEFINE';

    $editar_datos = true;
    if($banco_estado_id == 4){
        $editar_datos = false;
    }

}else{
    $tipoid = "";
    $identificacion = "";
    $nombre = "";
    $tipofac = "";
    $tipodoc = "";
    $numdoc = "";
    $fecha = $fecha_sistema;
    $categoria = "";
    $genero = "";
    $cantidad = "";
    $unidad = "";
    $descripcion = "";
    $vrunit = "";
    $vrtotal = "";
    $mediopago = "";
    $numsoporte = "";
    $fechaentrega = $fecha_sistema;
    $pvppublico = "";
    $urlimagen = "";
    $obs = "";
    $estado_id = "";
    $banco_estado_id = "";
    $caja2_estado_id = "";
    $lugar = "";

    $editar_datos = true;


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


            @if(Auth::user()->validar_permiso('dia_historico'))

            @isset($historicos)

            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalHistorico">
                <i class="fa fa-info-circle" aria-hidden="true"></i>Consultar Histórico Estados
            </button>
            <br><br>

            <!-- Modal -->
            <div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="modalHistoricoLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" style = 'width:80%;' role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHistoricoLabel">Histórico Cambio Estados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    @if ($historicos->isNotEmpty())
                        <div class = "table-responsive">
                            <table class="table table-hover table-bordered" id="tablaHistoricos">
                                <thead>
                                    <tr>
                                        <th >ID</th>
                                        <th >Tipo Estado</th>
                                        <th >Estado Antes</th>
                                        <th >Estado Despues</th>
                                        <th >Usuario</th>
                                        <th >Fecha</th>
                                    </tr>
                                    </thead> 
                                    <tbody>
                                    @foreach($historicos as $historico)
                                    <tr>
                                        <td>{{ $historico->id }}</td>
                                        <td>{{ $historico->tipo }}</td>
                                        <td>{{ $historico->estado_nombre($historico->estado_antes) }}</td>
                                        <td>{{ $historico->estado_nombre($historico->estado_ahora) }}</td>
                                        <td title = "{{$historico->usuario_nombre($historico->user_new)}}">{{ $historico->user_new }}</td>
                                        <td>{{ $historico->created_at }}</td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <p>No se encontraron registros.</p>
                    @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                    </div>
                </div>
            </div>
            <!-- Fin Modal -->
            @endisset
            @endif

            <!-- AQUI INICIA EL FORMULARIO  -->
            <form method="POST" id = "formulario" action="{{ $accion }}" files="true" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ $metodo }}
                @isset($opcion)
                <input type="hidden" id = "opcion" name = "opcion" value = "{{$opcion}}">
                @endisset
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Tipo Identificación</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <select <?php if(!$editar_datos) echo 'disabled'; ?> name="tipoid" id="tipoid" class = 'form-control'>
                                @foreach($tipo_identificaciones as $fila)
                                <option value="{{$fila->id}}" {{ old('tipoid',$tipoid) == $fila->id ? 'selected' : ''}}>{{$fila->nombre}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Identificación</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="number" class="form-control" name="identificacion" id="identificacion" value="{{ old('identificacion',$identificacion) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Nombre Cliente</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre',$nombre) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Tipo Factura</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <select <?php if(!$editar_datos) echo 'disabled'; ?> name="tipofac" id="tipofac" class = 'form-control' required>
                                @foreach($tipo_facturas as $fila)
                                <option value="{{$fila->id}}" {{ old('tipofac',$tipofac) == $fila->id ? 'selected' : ''}}>{{$fila->codigo.' :: '.$fila->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Tipo Documento</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <select <?php if(!$editar_datos) echo 'disabled'; ?> name="tipodoc" id="tipodoc" class = 'form-control' required>
                                <option value="" selected disabled>SELECCIONE</option>
                                @foreach($tipo_documentos as $fila)
                                <option value="{{$fila->id}}" {{ old('tipodoc',$tipodoc) == $fila->id ? 'selected' : ''}}>{{$fila->codigo.' :: '.$fila->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label># Factura</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="number" class="form-control" name="numdoc" id="numdoc" value="{{ old('numdoc',$numdoc) }}">
                        </div>
                    </div>
                    
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Categoría</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <select <?php if(!$editar_datos) echo 'disabled'; ?> name="categoria" id="categoria" class = 'form-control' required>
                                <option value="" selected disabled>SELECCIONE</option>
                                @foreach($categorias as $fila)
                                <option value="{{$fila->id}}" {{ old('categoria',$categoria) == $fila->id ? 'selected' : ''}}>{{$fila->codigo.' :: '.$fila->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Genero</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <select <?php if(!$editar_datos) echo 'disabled'; ?> name="genero" id="genero" class = 'form-control' required>
                                <option value="" selected disabled>SELECCIONE</option>
                                @foreach($generos as $fila)
                                <option value="{{$fila->id}}" {{ old('genero',$genero) == $fila->id ? 'selected' : ''}}>{{$fila->codigo.' :: '.$fila->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    
                    
                    
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Unidad</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <select <?php if(!$editar_datos) echo 'disabled'; ?> name="unidad" id="unidad" class = 'form-control' required>
                                @foreach($unidades as $fila)
                                <option value="{{$fila->id}}" {{ old('unidad',$unidad) == $fila->id ? 'selected' : ''}}>{{$fila->codigo.' :: '.$fila->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Cantidad</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="number" class="form-control" name="cantidad" id="cantidad" value="{{ old('cantidad',$cantidad) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Valor Unitario Principal (SIN IVA)</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="number" class="form-control" name="vrunit" id="vrunit" value="{{ old('vrunit',$vrunit) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Valor Total Factura</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="number" class="form-control" name="vrtotal" id="vrtotal" value="{{ old('vrtotal',$vrtotal) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Descripción</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="text" class="form-control" name="descripcion" id="descripcion" value="{{ old('descripcion',$descripcion) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>PVP Público Principal (CON IVA)</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input required <?php if(!$editar_datos) echo 'readonly'; ?> type="number" class="form-control" name="pvppublico" id="pvppublico" value="{{ old('pvppublico',$pvppublico) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Medio de Pago</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <select <?php if(!$editar_datos) echo 'disabled'; ?> name="mediopago" id="mediopago" class = 'form-control' required>
                                <option value="" disabled selected>SELECCIONE</option>
                                @foreach($medio_pagos as $medio_pago)
                                <option value="{{$medio_pago->id}}" {{ old('mediopago',$mediopago) == $medio_pago->id ? 'selected' : ''}}>{{$medio_pago->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label># Soporte</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input required  <?php if(!$editar_datos) echo 'readonly'; ?> type="text" class="form-control" name="numsoporte" id="numsoporte" value="{{ old('numsoporte',$numsoporte) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Adjuntar Soporte de Pago</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!isset($dia_iva)) echo 'required'; ?> <?php if(!$editar_datos) echo 'disabled'; ?> type="file" class="form-control" name="urlimagen" id="urlimagen" value="{{ old('urlimagen',$urlimagen) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Fecha</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="date" class="form-control" name="fecha" id="fecha" value="{{ old('fecha',$fecha) }}">
                        </div>
                    </div>
                    
                    
                    
                    
                    @isset($dia_iva)
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>LUGAR</label>
                            <input required readonly type="text" class="form-control" name="lugar" id="lugar" value="{{ old('lugar',$lugar) }}">
                        </div>
                    </div>
                    @endisset
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Observaciones (Otro Medio de Pago)</label>
                            <textarea <?php if(!$editar_datos) echo 'readonly'; ?> name="obs" id="obs"  rows="3" class = 'form-control'>{{ old('obs',$obs) }}</textarea>
                        </div>
                    </div>
                </div>
                <h3 class="h5-dark"> Control de Estados</h3>
                <div class = 'row'>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Estado Caja 1</label>
                            <select <?php if(!$editar_datos && !Auth::user()->validar_permiso('dia_revertir')) echo 'disabled'; ?> name="estado_id" id="estado_id" class = 'form-control'>
                                @foreach($iva_estados as $fila)
                                    @if($fila->tipo == 1 && $fila->id != 1)
                                    <option value="{{$fila->id}}" {{ old('estado_id',$estado_id) == $fila->id ? 'selected' : ''}}>{{$fila->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @isset($dia_iva)
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Estado Bancos</label>
                            @if(Auth::user()->validar_permiso('dia_autorizar'))
                            <select <?php if(!$editar_datos && !Auth::user()->validar_permiso('dia_revertir')) echo 'disabled'; ?> name="banco_estado_id" id="banco_estado_id" class = 'form-control'>
                                @foreach($iva_estados as $fila)
                                    @if($fila->tipo == 2)
                                    <option value="{{$fila->id}}" {{ old('banco_estado_id',$banco_estado_id) == $fila->id ? 'selected' : ''}}>{{$fila->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @else
                            <select disabled name="banco_estado_id" id="banco_estado_id" class = 'form-control'>
                                @foreach($iva_estados as $fila)
                                    @if($fila->tipo == 2)
                                    <option value="{{$fila->id}}" {{ old('banco_estado_id',$banco_estado_id) == $fila->id ? 'selected' : ''}}>{{$fila->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @endif
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Estado Caja 2</label>
                            @if($dia_iva->banco_estado_id == 4)
                            <select <?php if($caja2_estado_id == '6' && !Auth::user()->validar_permiso('dia_revertir')) echo 'disabled'; ?> name="caja2_estado_id" id="caja2_estado_id" class = 'form-control'>
                                @foreach($iva_estados as $fila)
                                    @if($fila->tipo == 3)
                                    <option value="{{$fila->id}}" {{ old('caja2_estado_id',$caja2_estado_id) == $fila->id ? 'selected' : ''}}>{{$fila->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @else
                            <select disabled name="caja2_estado_id" id="caja2_estado_id" class = 'form-control'>
                                @foreach($iva_estados as $fila)
                                    @if($fila->tipo == 3)
                                    <option value="{{$fila->id}}" {{ old('caja2_estado_id',$caja2_estado_id) == $fila->id ? 'selected' : ''}}>{{$fila->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @endif
                            
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Fecha Entrega</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if($editar_datos) echo 'readonly'; ?> required type="date" class="form-control" name="fechaentrega" id="fechaentrega" value="{{ old('fechaentrega',$fechaentrega) }}">
                        </div>
                    </div>
                    @endisset
                </div>


                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                <a href="{{ route('dia_ivas.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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

<script type="text/javascript">
    jQuery(function($) {
        $("#formulario").submit(function(){
            $("#alert-busqueda").show();
        });

        $('#tablaHistoricos').dataTable( {
            "order": [],
            "iDisplayLength": 10,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "_START_ al _END_ de  _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   " - filtro de _MAX_ registros",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        } );

        $("#tipoid").select2();
        $("#tipofac").select2();
        $("#tipodoc").select2();
        $("#categoria").select2();
        $("#genero").select2();
        $("#unidad").select2();
        $("#mediopago").select2();

        /**$("#cantidad,#vrunit").blur(function(){
            calcular_total();
        })**/
    });

    /**function calcular_total(){
        cantidad = parseInt($("#cantidad").val());
        valor = parseInt($("#vrunit").val());

        total = cantidad*valor;
        $("#vrtotal").val(total);

    }**/
    

</script>
@endsection



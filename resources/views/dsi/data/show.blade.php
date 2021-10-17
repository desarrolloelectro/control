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
    $tipoid = $dia_iva->tipo_identificacion != null ? $dia_iva->tipo_identificacion->nombre :'NO DEFINE';
    $identificacion = $dia_iva->identificacion;
    $nombre = $dia_iva->nombre;
    $tipofac = $dia_iva->tipo_factura != null ? $dia_iva->tipo_factura->codigo." :: ".$dia_iva->tipo_factura->nombre :'NO DEFINE';
    $tipodoc = $dia_iva->tipo_documento != null ? $dia_iva->tipo_documento->codigo." :: ".$dia_iva->tipo_documento->nombre :'NO DEFINE';
    $numdoc = $dia_iva->numdoc;
    $lugar = $dia_iva->tipo_documento != null ? $dia_iva->tipo_documento->codciu." :: ".$dia_iva->tipo_documento->ciudad." :: ".$dia_iva->tipo_documento->coddpto." :: ".$dia_iva->tipo_documento->depto :'NO DEFINE';
    $fecha = $dia_iva->fecha;
    $categoria = $dia_iva->categoria_nombre != null ? $dia_iva->categoria_nombre->codigo." :: ".$dia_iva->categoria_nombre->nombre :'NO DEFINE';
    $genero = $dia_iva->genero_nombre != null ? $dia_iva->genero_nombre->codigo." :: ".$dia_iva->genero_nombre->nombre :'NO DEFINE';
    $cantidad = $dia_iva->cantidad;
    $unidad = $dia_iva->unidad_nombre != null ? $dia_iva->unidad_nombre->codigo." :: ".$dia_iva->unidad_nombre->nombre :'NO DEFINE';
    $descripcion = $dia_iva->descripcion;
    $vrunit = $dia_iva->vrunit;
    $vrtotal = $dia_iva->vrtotal;
    $mediopago = $dia_iva->mediopago;
    $numsoporte = $dia_iva->numsoporte;
    $fechaentrega = $dia_iva->fechaentrega;
    $pvppublico = $dia_iva->pvppublico;
    $urlimagen = $dia_iva->urlimagen;
    $obs = $dia_iva->obs;
    $estado_id = $dia_iva->iva_estado != null ? $dia_iva->iva_estado->nombre :'NO DEFINE';
    $banco_estado_id = $dia_iva->banco_estado != null ? $dia_iva->banco_estado->nombre :'NO DEFINE';
    $caja2_estado_id = $dia_iva->caja2_estado != null ? $dia_iva->caja2_estado->nombre :'NO DEFINE';
}else{
    $nombre ="";
    $urlimagen ="";
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
            <div class = 'row'>
                <div class = 'col-md-9'>
                    <!-- AQUI INICIA EL FORMULARIO  -->
                    <form>
                        <div class = "row">
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>TIPOID</label>
                                    <input required readonly type="text" class="form-control" name="tipoid" id="tipoid" value="{{ old('tipoid',$tipoid) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>IDENTIFICACION</label>
                                    <input required readonly type="text" class="form-control" name="identificacion" id="identificacion" value="{{ old('identificacion',$identificacion) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>NOMBRE</label>
                                    <input required readonly type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre',$nombre) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>TIPOFAC</label>
                                    <input required readonly type="text" class="form-control" name="tipofac" id="tipofac" value="{{ old('tipofac',$tipofac) }}">
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>TIPODOC</label>
                                    <input required readonly type="text" class="form-control" name="tipodoc" id="tipodoc" value="{{ old('tipodoc',$tipodoc) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>NUMDOC</label>
                                    <input required readonly type="text" class="form-control" name="numdoc" id="numdoc" value="{{ old('numdoc',$numdoc) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>CATEGORIA</label>
                                    <input required readonly type="text" class="form-control" name="categoria" id="categoria" value="{{ old('categoria',$categoria) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>GENERO</label>
                                    <input required readonly type="text" class="form-control" name="genero" id="genero" value="{{ old('genero',$genero) }}">
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>UNIDAD</label>
                                    <input required readonly type="text" class="form-control" name="unidad" id="unidad" value="{{ old('unidad',$unidad) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>CANTIDAD</label>
                                    <input required readonly type="text" class="form-control" name="cantidad" id="cantidad" value="{{ old('cantidad',$cantidad) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>VRUNIT</label>
                                    <input required readonly type="text" class="form-control" name="vrunit" id="vrunit" value="{{ old('vrunit',$vrunit) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>VRTOTAL</label>
                                    <input required readonly type="text" class="form-control" name="vrtotal" id="vrtotal" value="{{ old('vrtotal',$vrtotal) }}">
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-12">
                                <div class="form-group">
                                    <label>DESCRIPCION</label>
                                    <input required readonly type="text" class="form-control" name="descripcion" id="descripcion" value="{{ old('descripcion',$descripcion) }}">
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>PVPPUBLICO</label>
                                    <input required readonly type="text" class="form-control" name="pvppublico" id="pvppublico" value="{{ old('pvppublico',$pvppublico) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>MEDIOPAGO</label>
                                    <select disabled name="mediopago" id="mediopago" class = 'form-control'>
                                        <option value="0" selected>NO DEFINE</option>
                                        @foreach($medio_pagos as $medio_pago)
                                        <option value="{{$medio_pago->id}}" {{ old('mediopago',$mediopago) == $medio_pago->id ? 'selected' : ''}}>{{$medio_pago->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>NUMSOPORTE</label>
                                    <input type="text" readonly class="form-control" name="numsoporte" id="numsoporte" value="{{ old('numsoporte',$numsoporte) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>LUGAR</label>
                                    <input required readonly type="text" class="form-control" name="lugar" id="lugar" value="{{ old('lugar',$lugar) }}">
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>FECHA</label>
                                    <input required readonly type="text" class="form-control" name="fecha" id="fecha" value="{{ old('fecha',$fecha) }}">
                                </div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-12">
                                <div class="form-group">
                                    <label>OBSERVACIONES</label>
                                    <textarea readonly name="obs" id="obs"  rows="3" class = 'form-control'>{{ old('obs',$obs) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <h3 class="h5-dark"> Control de Estados</h3>
                        <div class = 'row'>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>ESTADO CAJA</label>
                                    <input required readonly type="text" class="form-control" value="{{ old('estado_id',$estado_id) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>ESTADO BANCOS</label>
                                    <input required readonly type="text" class="form-control" value="{{ old('banco_estado_id',$banco_estado_id) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>ESTADO CAJA2</label>
                                    <input required readonly type="text" class="form-control" value="{{ old('caja2_estado_id',$caja2_estado_id) }}">
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class="form-group">
                                    <label>FECHAENTREGA</label>
                                    <input required readonly type="text" class="form-control" name="fechaentrega" id="fechaentrega" value="{{ old('fechaentrega',$fechaentrega) }}">
                                </div>
                            </div>
                        </div>
                        @if(session()->has('mensaje'))
                            <div class="alert alert-success">
                                {{ session()->get('mensaje') }}
                            </div>
                        @endif
                        <a href="{{ route('dia_ivas.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
                        <span  style = "display:none;" id = "alert-busqueda">
                            Cargando...
                            <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
                        </span>
                    </form>
                    <!-- AQUI CIERRA EL FORMULARIO  -->
                </div>
                <div class = 'col-md-3'>                    
                    @if($dia_iva->urlimagen != null && $dia_iva->urlimagen != '')
                    <center>
                        <h4>ARCHIVO CARGADO</h4>
                        <img style = 'width:100%;' src="{{ asset('uploads/archivos') }}/{{$dia_iva->urlimagen}}" alt="">
                        <a class="btn2 btn-dark btn-block" target = "_blank" href="{{ asset('uploads/archivos') }}/{{$dia_iva->urlimagen}}" class = "bold"><i class = "fa fa-search"></i> VER</a>
                    </center>
                    @else
                    <div class = 'alert alert-info'>No se ha cargado ningun archivo.</div>
                    @endif
                </div>
            </div>
            </div>
          </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($) {
        $("#formulario").submit(function(){
            $("#alert-busqueda").show();
        })
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
    });
</script>
@endsection
@extends('layout')
@section('content')
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
            @if(Auth::check())
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
                            <label>Nombre</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="text" class="form-control" name="name" id="name" value="{{ old('name',$dsi->name) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Fecha del día</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input <?php if(!$editar_datos) echo 'readonly'; ?> required type="date" class="form-control" name="date" id="date" value="{{ old('date',$dsi->date) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Permiso</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <select <?php if(!$editar_datos) echo 'disabled'; ?> name="permission" id="permission" class = 'form-control' required>
                                <option value="">Seleccione un permiso</option>
                                @foreach($permisos as $permiso)
                                <option value="{{ $permiso->codigo }}" {{ old( 'permission', $dsi->permission) == $permiso->codigo ? 'selected' : ''}}>{{ $permiso->nombre." (".$permiso->codigo.")" }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Estado</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <select <?php if(!$editar_datos) echo 'disabled'; ?> name="state" id="state" class = 'form-control' required>
                                <option value="">Seleccione un estado</option>
                                <option value="1" {{ old( 'state', $dsi->state) == 1 ? 'selected' : ''}}>Activo</option>
                                <option value="0" {{ old( 'state', $dsi->state) == 0 ? 'selected' : ''}}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Creado por</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input readonly required type="text" class="form-control" id="created_by" value="{{$dsi->created_by }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Actualizado por</label> <i class = 'fa fa-asterisk' style = 'color:red;font-size:10px;'></i>
                            <input readonly required type="text" class="form-control" id="updated_by" value="{{ $dsi->updated_by }}">
                        </div>
                    </div>
                </div>
                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                <a href="{{ route('dsi.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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
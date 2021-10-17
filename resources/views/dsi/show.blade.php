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
                <form method="POST" id="formulario" action="{{ $accion }}">
                {{ csrf_field() }}
                {{ $metodo }}
                <h1>Datos de Día sin IVA</h1>
                <h2>Campos Básicos</h2>
                @php
                $fields = json_decode($dsi->fields,true);
                $fields_report = json_decode($dsi->fields_report,true);
                $fields_view = json_decode($dsi->fields_view,true);
                $meta_fields = json_decode($dsi->meta_fields,true);
                $meta_fields_report = json_decode($dsi->meta_fields_report,true);
                $meta_fields_view = json_decode($dsi->meta_fields_view,true);
                @endphp
                 <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Ver en Formulario</th>
                            <th>Ver en Reporte Vista</th>
                            <th>Ver en Reporte Excel</th>
                        </tr>
                    </thead>
                    <tbody>
                   
                    @foreach($fields_data as $id => $field)
                    @php
                    if(!empty($fields) && in_array($id,$fields)){
                        $checked_f = "checked";
                    }else{
                        $checked_f = "";
                    }
                    if(!empty($fields_report) && in_array($id,$fields_report)){
                        $checked_fr = "checked";
                    }else{
                        $checked_fr = "";
                    }
                    if(!empty($fields_view) && in_array($id,$fields_view)){
                        $checked_vfr = "checked";
                    }else{
                        $checked_vfr = "";
                    }
                    @endphp

                        <tr>
                            <td>{{ $field  }}</td>
                            <td><input type="checkbox"  {{ $checked_f  }} name="fields[]" value="{{ $id }}"></td>
                            <td><input type="checkbox" {{ $checked_vfr  }} name="fields_view[]" value="{{ $id }}"></td>
                            <td><input type="checkbox" {{ $checked_fr  }} name="fields_report[]" value="{{ $id }}"></td>
                        </tr>
                    @endforeach
                    
                    </tbody>
                </table>
                
              @if($enable_meta)
                <h2 style="display:inline" >Campos Adicionales</h2>&nbsp;&nbsp;&nbsp;<a title="Configurar campos adicionales" style="display:inline;float:right;" href="{{ route('dsi.meta.index',['dsi_id' => $dsi->id]) }}"><button class="btn btn-success" type = "button">Configurar</button></a>
                <br>
                @if(!empty($dsi->metas) && count($dsi->metas)>0)
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Tipo</th>
                            <th>Ver en Formulario</th>
                            <th>Ver en Reporte Vista</th>
                            <th>Ver en Reporte Excel</th>
                        </tr>
                    </thead>
                    <tbody>
                
                @foreach($dsi->metas as $id => $meta)
                    @php
                        if(!empty($meta_fields) && in_array($meta->id,$meta_fields)){
                            $checked_mf = "checked";
                        }else{
                            $checked_mf = "";
                        }
                        if(!empty($meta_fields_report) && in_array($meta->id,$meta_fields_report)){
                            $checked_mfr = "checked";
                        }else{
                            $checked_mfr = "";
                        }
                        if(!empty($meta_fields_view) && in_array($meta->id,$meta_fields_view)){
                            $checked_mvfr = "checked";
                        }else{
                            $checked_mvfr = "";
                        }
                        $options = json_decode($meta->options);
                    @endphp
                        <tr>
                            <td>{{ $meta->field_name }}</td>
                            <td>{{ $types[$meta->type] }} @if(!empty($options))
                                <br><small>{{ "Opciones" }}</small>
                                <ul>
                                @foreach($options as $option)
                                <li>{{ $option }}</li>
                                @endforeach 
                                </ul>
                            @endif</td>
                            <td><input type="checkbox" {{ $checked_mf  }} name="meta_fields[]" value="{{$meta->id }}"></td>
                            <td><input type="checkbox" {{ $checked_mvfr  }} name="meta_fields_view[]" value="{{$meta->id }}"></td>
                            <td><input type="checkbox" {{ $checked_mfr  }} name="meta_fields_report[]" value="{{ $meta->id }}"></td>
                        </tr>    
                @endforeach
                    </tbody>
                </table>
                @else
                <p>No tiene campos adicionales</p>
                @endif
            @endif
                <button class="btn btn-primary" type="submit">Actualziar</button>
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
                "sInfoThousands":  ",
               {",}
              [ "sLoadingRecords", "oPaginate": {]

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
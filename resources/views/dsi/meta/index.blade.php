@extends('layout')
@section('content')

<div class="app-title">
    <div>
            <h1>
                {!! $title !!}            
            </h1>
        @if(Auth::user()->validar_permiso('dia_rep_nov'))
            @if(2 == 1)
            <a href="{{ route('dsi.importar') }}" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i>Subir Archivo</a>
            @endif
        @endif
    </div>
</div>

<div class = "row">
<div class="col-md-12">
    <div class="tile">
    <div class="tile-body">
    <div class="row">
        <div class="col-md-10">
            </div>
        <div class="col-md-2">
            <button class="btn btn-primary toggler" toggle-target="form_create"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4">
            @include('dsi.meta.create',['types' => $types])
        </div>
    </div>
    <!--br>
    <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <input type="date" class="form-control" placeholder="Buscar por fecha" id="buscar_paginacion_fecha" value="">
                <div class="input-group-append"><button type="button" onclick="document.getElementById('buscar_paginacion_fecha').value='';" class="btn btn-default">X</button></div>
            </div>
        </div>
        <div class='col-md-4' >
            <input type="text" class="form-control" placeholder="Buscar por responsable" id="buscar_paginacion" value="">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Buscar por nombre" id="buscar_paginacion_name" value="">
        </div>
    </div>
    <br-->
    
    @if (!empty($dsi))
    <div class = "table-responsive">
        <table class="table table-hover table-bordered table-striped" id="sampleTable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th style="min-width:90px!important;">Acciones</th>
                </tr>
            </thead> 
            
            <tbody>
                @foreach($dsi_metas as $dsi_meta)
                @php
                $options = json_decode($dsi_meta->options);
                @endphp
                <tr 
                @if($dsi_meta->deleted_by!="")
                style="text-decoration: line-through;" 
                title="Eliminado por {{ $dsi_meta->deleted_by }} en la fecha: {{ custom_date_format($dsi_meta->deleted_at) }}"
                @endif
                >
                    <td>{{ $dsi_meta->field_name }}</td>
                    <td>{{ $types[$dsi_meta->type] }} @if(!empty($options))
                                <br><small>{{ "Opciones" }}</small>
                                <ul>
                                @foreach($options as $option)
                                <li>{{ $option }}</li>
                                @endforeach 
                                </ul>
                            @endif</td>
                    <td>
                        <form  style="display:inline;" id="form{{$dsi_meta->id}}" class="form-table" action="{{ route('dsi.meta.destroy', ['dsi_id' => $dsi_meta->dsi_id, 'id' => $dsi_meta->id]) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                                @if(Auth::user()->validar_permiso('dsi_meta_edit') || true)
                                <a class="btn2 btn-success" title="Modificar" href="{{ route('dsi.meta.edit',['dsi_id' => $dsi_meta->dsi_id, 'id' => $dsi_meta->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                @endif
                                @if(Auth::user()->validar_permiso('dsi_meta_history') || true)
                                <a class="btn2 btn-warning" title="Historial" href="{{ route('dsi.meta.history',['dsi_id' => $dsi_meta->dsi_id, 'id' => $dsi_meta->id]) }}"><i class="fa fa-history" aria-hidden="true"></i></a>
                                @endif
                                @if(Auth::user()->validar_permiso('dsi_meta_delete') || true)
                                <a class="btn2 btn-danger"  title="Eliminar" href="" onclick="eliminar({{$dsi_meta->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                                @endif
                                @if(Auth::user()->validar_permiso('dsi_meta_restore_deleted') || true)
                                <a class="btn2 btn-danger"  title="Restaurar Eliminado" action="{{ route('dsi.meta.restore', ['dsi_id' => $dsi_meta->dsi_id, 'id' => $dsi_meta->id]) }}" id="restore{{$dsi_meta->id}}" href="" onclick="restaurar({{$dsi_meta->id}},event)"><i class="fa green fa-undo" aria-hidden="true"></i></a>
                                @endif
                            
                        </form>
                    </td>
                </tr>
                @if(!empty($dsi_meta->childs))
                        @php $dsi_meta->childs(); @endphp
                        @foreach($dsi_meta->childs as $dsi_meta_child)
                            @php
                                $options = json_decode($dsi_meta_child->options);
                            @endphp
                            <tr 
                            @if($dsi_meta_child->deleted_by!="")
                            style="text-decoration: line-through;" 
                            title="Eliminado por {{ $dsi_meta_child->deleted_by }} en la fecha: {{ custom_date_format($dsi_meta_child->deleted_at) }}"
                            @endif
                            >
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;-{{ $dsi_meta_child->field_name }} (Depende de {{ $dsi_meta->field_name }} con valor {{ $dsi_meta_child->parent_value }})</td>
                                <td>{{ $types[$dsi_meta_child->type] }} @if(!empty($options))
                                            <br><small>{{ "Opciones" }}</small>
                                            <ul>
                                                @foreach($options as $option)
                                                <li>{{ $option }}</li>
                                                @endforeach 
                                            </ul>
                                        @endif</td>
                                <td>
                                    <form  style="display:inline;" id="form{{$dsi_meta_child->id}}" class="form-table" action="{{ route('dsi.meta.destroy', ['dsi_id' => $dsi_meta_child->dsi_id, 'id' => $dsi_meta_child->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                            @if(Auth::user()->validar_permiso('dsi_meta_edit') || true)
                                            <a class="btn2 btn-success" title="Modificar" href="{{ route('dsi.meta.edit',['dsi_id' => $dsi_meta_child->dsi_id, 'id' => $dsi_meta_child->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                            @endif
                                            @if(Auth::user()->validar_permiso('dsi_meta_history') || true)
                                            <a class="btn2 btn-warning" title="Historial" href="{{ route('dsi.meta.history',['dsi_id' => $dsi_meta_child->dsi_id, 'id' => $dsi_meta_child->id]) }}"><i class="fa fa-history" aria-hidden="true"></i></a>
                                            @endif
                                            @if(Auth::user()->validar_permiso('dsi_meta_delete') || true)
                                            <a class="btn2 btn-danger"  title="Eliminar" href="" onclick="eliminar({{$dsi_meta_child->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                                            @endif
                                            @if(Auth::user()->validar_permiso('dsi_meta_restore_deleted') || true)
                                            <a class="btn2 btn-danger"  title="Restaurar Eliminado" action="{{ route('dsi.meta.restore', ['dsi_id' => $dsi_meta_child->dsi_id, 'id' => $dsi_meta_child->id]) }}" id="restore{{$dsi_meta_child->id}}" href="" onclick="restaurar({{$dsi_meta_child->id}},event)"><i class="fa green fa-undo" aria-hidden="true"></i></a>
                                            @endif
                                        
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
        {{ $dsi_metas->links() }}
        @if(session()->has('mensaje'))
            <div class="alert alert-success">
                {{ session()->get('mensaje') }}
            </div>
        @endif
        @if(session()->has('opcion'))
                    <input type="hidden" id = "cerrar" value = "{{ session()->get('opcion') }}">
        @endif
    </div>
        @if(session()->has('alerta'))
            <div class="alert alert-danger">
                {{ session()->get('alerta') }}
            </div>
        @endif
    @else
        <p>No se contraron registros.</p>
    @endif
    <span  style = "display:none;" id = "alert-busqueda">
        Cargando...
        <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
    </span>
    <div class="tile-footer">
    </div>
    </div>
    </div>
</div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        $('#buscar_paginacion').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                buscar_filtro();
            }
        });
        $('#buscar_paginacion_name').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                buscar_filtro();
            }
        });
        
        $("#medio_pago_id").change(function(){
            buscar_filtro();
        });
        $("#buscar_paginacion_fecha").change(function(){
            buscar_filtro();
        });
    });

    function buscar_filtro(){
        valor = $('#buscar_paginacion').val();
        fecha = $('#buscar_paginacion_fecha').val();
        name = $('#buscar_paginacion_name').val();
        medio_pago_id = $('#medio_pago_id').val();
        url = "{{ 'url_paginacion' }}";
        url = url + "?search="+valor+"&date="+fecha+"&name="+name;
        $(location).attr('href',url);
    }

    function eliminar(id,event){
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
                    $("#alert-busqueda").show();
                    $("#form"+id).submit();
                }
            }
        });
    }

    function restaurar(id,event,value=''){
        event.preventDefault();
        bootbox.confirm({
            message: "Está seguro que desea restaurar el registro?",
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
                    $("#alert-busqueda").show();
                    $("#form"+id).attr('action', $("#restore"+id).attr('action'));
                    $("#form"+id).submit();
                }
            }
        });
    }
</script>
@endsection
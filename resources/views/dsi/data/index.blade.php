@extends('layout')
@section('content')
@php
$fields_view = json_decode($dsi->fields_view,true);
$meta_fields_view = json_decode($dsi->meta_fields_view,true);
$dsi->metas();
$permiso_show = \App\DsiPermission::dsi_permiso($dsi->id,'dsi.data.show');
$permiso_edit = \App\DsiPermission::dsi_permiso($dsi->id,'dsi.data.edit');
$permiso_authorize = \App\DsiPermission::dsi_permiso($dsi->id,'dsi.data.authorize');
$permiso_reverse = \App\DsiPermission::dsi_permiso($dsi->id,'dsi.data.reverse');
$permiso_history = \App\DsiPermission::dsi_permiso($dsi->id,'dsi.data.history');
$permiso_archive = \App\DsiPermission::dsi_permiso($dsi->id,'dsi.data.archive');
$permiso_report = \App\DsiPermission::dsi_permiso($dsi->id,'dsi.data.report');
@endphp
<div class="app-title">
    <div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dsi.index') }}"><i class="icon fa fa-shopping-bag"></i> Días sin IVA</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
        </ol>
    </nav>
        
    <a href="{{ route('dsi.data.create', ['dsi' => $dsi->id] ) }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo</a>
    @if(Auth::user()->validar_permiso($permiso_report))
    <form style="display:inline;"  id="form_rep{{ $dsi->id }}" class = "form-table" action="{{ route('reportes.dsi_export') }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('POST') }}
        <input type="hidden" name="id" value="{{ $dsi->id }}">
        <input type="hidden" name="permission" value="{{ $dsi->permission }}">
        <input type="hidden" name="filename" value="{{ $dsi->name }}">
        <a style="color: #FFF;cursor:pointer;" onclick="document.getElementById('form_rep{{ $dsi->id }}').submit();" class="btn btn-success" title="Descargar Informe"><i class="fa fa-download" aria-hidden="true"></i>Descargar Informe</a>
    </form>
    @if(2 == 1)
    <a href="{{ route('dsi.data.importar') }}" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i>Subir Archivo</a>
    @endif
    <a href="{{ route('dsi.show', ['id' => $dsi->id]) }}" class="btn btn-info"><i class="fa fa-search-plus" aria-hidden="true"></i>Detalles</a>
    @endif
    </div>
</div>

<div class = "row">
<div class="col-md-12">
    <div class="tile">
    <div class="tile-body">
    <div class="row">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Buscar" id = "buscar_paginacion" value = "{{$valor}}">
        </div>
        <div class = 'col-md-4'>
            <select name="medio_pago_id" id="medio_pago_id" class = 'form-control' required>
                <option value="0" selected>TODOS LOS MEDIOS DE PAGO</option>
                @foreach($medio_pagos as $medio_pago)
                <option value="{{ $medio_pago->id }}" {{ old('medio_pago_id', $medio_pago_id) == $medio_pago->id ? 'selected' : ''}}>{{$medio_pago->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" readonly class="form-control" value = "TOTAL REGISTROS: {{$dsi_data->total()}}">
        </div>
    </div>
    <br>
    @if ($dsi_data->isNotEmpty())
    <button title="Para desplazarse horizontalmente utilice las flechas o presione la tecla Shift + Scrool de su mouse" class="btn2 btn-success" id="left-button"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
    <button title="Para desplazarse horizontalmente utilice las flechas o presione la tecla Shift + Scrool de su mouse" class="btn2 btn-success" id="right-button"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
    <div class = "table-responsive" style="height: 400px !important;">
        <table class="table table-hover table-bordered table-striped" id="sampleTable">
            <!--    COMIENZA CONTENIDO TABLA   -->
            <thead>
                <tr >
                    @foreach($fields_view as $field_view)
                    <th>{!! $fields[$field_view] !!}</th>
                    @endforeach
                    @foreach($dsi->metas as $dsi_meta)
                        @if(in_array($dsi_meta->id,$meta_fields_view))
                            <th>{!! $dsi_meta->field_name !!}</th>
                            @php $dsi_meta->childs(); @endphp
                            @if(!empty($dsi_meta->childs))
                                @foreach($dsi_meta->childs as $dsi_meta_child)
                                        <th>{!! $dsi_meta_child->field_name !!}</th>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                    <th style = "min-width:115px!important;">Acciones</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($dsi_data as $dia_iva)
                <tr dsi="{{$dia_iva->dsi_id}}" dsi_data_id="{{$dia_iva->id}}">
                    @foreach($fields_view as $field_view) @php $field_view_ft = $field_view."_ft"; $field_view_fst = $field_view."_fst"; @endphp 
                        @if(isset($dia_iva->$field_view_fst)) <?php /** Campo con Formato y Estilos */?>
                            <td>{!! $dia_iva->$field_view_fst !!}</td>
                        @elseif(isset($dia_iva->$field_view_ft)) <?php /**Campo con Formato */?>
                            <td>{{ $dia_iva->$field_view_ft }}</td>
                        @else <?php /** Campo normal */?>
                            <td>{{ $dia_iva->$field_view }}</td>
                        @endif
                    @endforeach
                    @foreach($dsi->metas as $dsi_meta)
                        @if(in_array($dsi_meta->id,$meta_fields_view))
                            @php 
                                $dia_iva->dsi_meta_value($dsi_meta->id); 
                                $dsi_meta->childs();
                            @endphp
                            <td>{!! (isset($dia_iva->dsi_meta_value->value)) ? ($dia_iva->dsi_meta_value->value) : ''; !!}</td>
                            @if(!empty($dsi_meta->childs))
                                @foreach($dsi_meta->childs as $dsi_meta_child)
                                @php 
                                $dia_iva->dsi_meta_value($dsi_meta_child->id); 
                                @endphp 
                                        <td>{!! (isset($dia_iva->dsi_meta_value->value)) ? ($dia_iva->dsi_meta_value->value) : ''; !!}</td>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                    <td>
                        <form id = "form{{$dia_iva->id}}" class = "form-table" action="{{ route('dsi.data.destroy', $dia_iva->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            
                            @if(Auth::user()->validar_permiso($permiso_show))
                            <a class="btn2 btn-info" title = "Detalle" href="{{ route('dsi.data.show',['dsi_id'=>$dia_iva->dsi_id, 'id'=>$dia_iva->id]) }}"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                            @endif
                            
                            @if(Auth::user()->validar_permiso($permiso_edit || $permiso_authorize || $permiso_reverse))
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('dsi.data.edit',['dsi_id'=>$dia_iva->dsi_id,'id'=>$dia_iva->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            @endif
                            @if(Auth::user()->validar_permiso($permiso_history))
                                @if(count($dia_iva->histories)>0)
                                <a class="btn2 btn-warning" title="Historial" href="{{ route('dsi.data.history',['dsi_id'=>$dia_iva->dsi_id,'id'=>$dia_iva->id]) }}"><i class="fa fa-history" aria-hidden="true"></i></a>
                                @endif
                            @endif
                            @if(Auth::user()->validar_permiso($permiso_archive))
                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar({{$dia_iva->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <!--    FIN CONTENIDO TABLA   -->
        </table>
        {{$dsi_data->links()}}
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
                $("#medio_pago_id").change(function(){
                    buscar_filtro();
                });
			});
            function buscar_filtro(){
                valor = $('#buscar_paginacion').val();
                medio_pago_id = $('#medio_pago_id').val();
                url = "{{ $url_paginacion }}";
                url = url + "?search="+valor+"&medio_pago_id="+medio_pago_id;
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
            $("#right-button").click(function() {
            event.preventDefault();
            $(".table-responsive").animate(
                {
                scrollLeft: "+=800px"
                },
                "fast"
            );
            });

            $("#left-button").click(function() {
            event.preventDefault();
            $(".table-responsive").animate(
                {
                scrollLeft: "-=800px"
                },
                "fast"
            );
            });
            /*
            $("#right-button").mouseover(function() {
                event.preventDefault();
                var intervalID = window.setInterval(function(){
                    if ($("#right-button").is(':hover')) {
                        $(".table-responsive").animate(
                            {
                            scrollLeft: "+=150px"
                            },
                            "fast"
                        );
                    }
                }, 400);
            });
            $("#left-button").mouseover(function() {
                event.preventDefault();
                var intervalID = window.setInterval(function(){
                    if ($("#left-button").is(':hover')) {                       
                        $(".table-responsive").animate(
                            {
                            scrollLeft: "-=150px"
                            },
                            "fast"
                        );
                    }
                }, 400);       
            });
            */
		</script>
@endsection









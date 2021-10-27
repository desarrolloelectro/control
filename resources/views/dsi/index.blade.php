@extends('layout')
@section('content')
<div class="app-title">
    <div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">{!! $title !!}</li>
        </ol>
    </nav>
            <?php /* importar */ ?>
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
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
            <a role="button" class="btn btn-info" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Días sin IVA Antes de Obtubre de 2021
            </a>
          </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <ul style="list-style:none;">
                    <li><a class="app-menu__item <?php if (isset($controlador) && $controlador == 'dia_ivas') echo 'active';?>" href="{{ route('dia_ivas.index') }}"><i class="app-menu__icon fa fa-book"></i><span class="">Día sin IVA Noviembre</span></a></li>
                    <li><a class="app-menu__item <?php if (isset($controlador) && $controlador == 'informe_ventas') echo 'active';?>" href="{{ route('informe_ventas.index') }}"><i class="app-menu__icon fa fa-book"></i><span class="">Día sin IVA Julio</span></a></li>
                </ul>
            </div>
          </div>
        </div>
        </div>
        </div>
        <div class="col-md-2">
            @php
            /** OJO Editar en controlador */
            $permiso_create = \App\DsiPermission::dsi_permiso(0,'dsi.create');
            @endphp
            @if(Auth::user()->validar_permiso($permiso_create))
            <a href="{{ route('dsi.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo</a>
            @endif
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <input type="date" class="form-control" placeholder="Buscar por fecha" id="buscar_paginacion_fecha" value="{{$fecha}}">
                <div class="input-group-append"><button type="button" id="buscar_sin_fecha" class="btn btn-default">X</button></div>
            </div>
        </div>
        <div class='col-md-4' >
            <input type="text" class="form-control" placeholder="Buscar por responsable" id="buscar_paginacion" value="{{$valor}}">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Buscar por nombre" id="buscar_paginacion_name" value="{{$valorname}}">
        </div>
    </div>
    <br>
    @if (!empty($dsi))
    <div class = "table-responsive">
        <table class="table table-hover table-bordered table-striped" id="sampleTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha del día sin IVA</th>
                    <th>Total Registros</th>
                    <th>Estado</th>
                    <th>Permiso</th>
                    <th>Creado por /<br>Fecha de creación</th>
                    <th>Última actualización:<br> Responsable /<br>Fecha</th>
                    <th style="min-width:160px!important;">Acciones</th>
                </tr>
            </thead> 
            <tbody>
                @foreach($dsi as $dia_iva)
                @php
                    $dia_iva->histories();
                    $permiso_view = \App\DsiPermission::dsi_permiso($dia_iva->id,'dsi.data.view');
                    $permiso_history = \App\DsiPermission::dsi_permiso($dia_iva->id,'dsi.data.history');
                    $permiso_edit = \App\DsiPermission::dsi_permiso($dia_iva->id,'dsi.data.edit');
                    $permiso_authorize = \App\DsiPermission::dsi_permiso($dia_iva->id,'dsi.data.authorize');
                    $permiso_reverse = \App\DsiPermission::dsi_permiso($dia_iva->id,'dsi.data.reverse');
                    $permiso_delete = \App\DsiPermission::dsi_permiso($dia_iva->id,'dsi.data.delete');
                    $permiso_restore = \App\DsiPermission::dsi_permiso($dia_iva->id,'dsi.data.restore');
                @endphp
                <tr dsi="{{$dia_iva->id}}"
                @if($dia_iva->state==1 && Auth::user()->validar_permiso($permiso_view))
                    title="Abrir" 
                    class="cursor-pointer" 
                    onclick="document.location.href='{{ route('dsi.data.index',['id'=>$dia_iva->id]) }}';"
                @endif
                @if($dia_iva->deleted_by!="")
                style="text-decoration: line-through;" 
                title="Eliminado por {{ $dia_iva->deleted_by }} en la fecha: {{ custom_date_format($dia_iva->deleted_at) }}"
                @endif
                >
                    <td>{{ $dia_iva->id }}</td>
                    <td>{{ $dia_iva->name }}</td>
                    <td>{{ custom_date_format($dia_iva->date, "d/m/Y") }}</td>
                    <td>{{ $dia_iva->last_id }}</td>
                    <td>{{ $dia_iva->states() }}</td>
                    <td>{!! !empty($dia_iva->permiso) ? $dia_iva->permiso->nombre."<br>(".$dia_iva->permission.")" : "" !!}</td>
                    <td><strong>{{ $dia_iva->created_by }}</strong> /<br>{{ custom_date_format($dia_iva->created_at) }}</td>
                    <td><strong>{{ $dia_iva->updated_by }}</strong> /<br>{{ custom_date_format($dia_iva->updated_at) }}</td>
                    <td>
                            @if($dia_iva->state==1 && Auth::user()->validar_permiso($dia_iva->permission))
                                <a class="btn2 btn-secondary" title="Abrir" href="{{ route('dsi.data.index',['id'=>$dia_iva->id]) }}"><i class="fa fa-folder-open" aria-hidden="true"></i></a>
                            @endif
                        <form style="display:inline;" id="form_rep{{$dia_iva->id}}" class="form-table" action="{{ route('reportes.dsi_export') }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" name="id" value="{{$dia_iva->id}}">
                            <input type="hidden" name="permission" value="{{$dia_iva->permission}}">
                            <input type="hidden" name="filename" value="{{$dia_iva->name}}">
                            <a style="color: #FFF;cursor:pointer;" onclick="document.getElementById('form_rep{{$dia_iva->id}}').submit();" class="btn2 btn-primary" title="Descargar Informe"><i class="fa fa-download" aria-hidden="true"></i></a>
                        </form>

                        <form  style="display:inline;" id="form{{$dia_iva->id}}" class = "form-table" action="{{ route('dsi.destroy', $dia_iva->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a class="btn2 btn-info" title = "Detalle" href="{{ route('dsi.show',['id'=>$dia_iva->id]) }}"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                            @if($dia_iva->deleted_by=="")
                               
                                @if (Auth::user()->validar_permiso($permiso_history))
                                    @if(count($dia_iva->histories)>0)
                                    <a class="btn2 btn-warning" title="Historial" href="{{ route('dsi.history',['id'=>$dia_iva->id]) }}"><i class="fa fa-history" aria-hidden="true"></i></a>
                                    @endif
                                @endif

                                @if(Auth::user()->validar_permiso($permiso_edit || $permiso_authorize || $permiso_reverse))
                                <a class="btn2 btn-success" title="Modificar" href="{{ route('dsi.edit',['id'=>$dia_iva->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                @endif
                                @if(Auth::user()->validar_permiso($permiso_delete))
                                <a class="btn2 btn-danger"  title="Eliminar" href="" onclick="eliminar({{$dia_iva->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                                @endif
                            @else
                                @if(Auth::user()->validar_permiso($permiso_restore))
                                <a class="btn2 btn-danger"  title="Restaurar Eliminado" action="{{ route('dsi.restore', $dia_iva->id) }}" id="restore{{$dia_iva->id}}" href="" onclick="restaurar({{$dia_iva->id}},event)"><i class="fa green fa-undo" aria-hidden="true"></i></a>
                                @endif
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$dsi->links()}}
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
<style>
    .cursor-pointer{
        cursor:pointer !important;
    }
</style>
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
        $("#buscar_sin_fecha").click(function(){
            document.getElementById('buscar_paginacion_fecha').value='';
            buscar_filtro();
        });
    });

    function buscar_filtro(){
        valor = $('#buscar_paginacion').val();
        fecha = $('#buscar_paginacion_fecha').val();
        name = $('#buscar_paginacion_name').val();
        medio_pago_id = $('#medio_pago_id').val();
        url = "{{ $url_paginacion }}";
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
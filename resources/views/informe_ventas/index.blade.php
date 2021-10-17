@extends('layout')

@section('content')
<div class="app-title">
    <div>
        <h1>
            {{ $title }}
            @if(2==1)
            <a href="{{ route('informe_ventas.importar') }}" class="btn btn-primary"><i class="fa fa-upload" aria-hidden="true"></i>Cargar Informe</a>
            @endif
            <a href="{{ route('reportes.informe_ventas_export') }}" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i>Descargar Informe</a>

        </h1>
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
        <div class="col-md-4">
            <input type="text" readonly class="form-control" value = "TOTAL REGISTROS: {{$informe_ventas->total()}}">
        </div>
    </div>
    <br>
    @if ($informe_ventas->isNotEmpty())
    <div class = "table-responsive">
        <table class="table table-hover table-bordered" id="sampleTable">
            

            <!--    COMIENZA CONTENIDO TABLA   -->
            <thead>
                <tr>
                    <th style = "min-width:90px!important;">Acciones</th>
                    <th >tipodoc</th>
                    <th >numdoc</th>
                    <th >vrunit</th>
                    <th >identificacion</th>
                    <th >nombre</th>
                    <th >vrtotal</th>
                    <th >lugar</th>
                    <th style = "min-width:80px!important;">fecha</th>
                    <th >cantidad</th>
                    <th >descripcion</th>
                    <th >Archivo</th>
                    <th style = "min-width:90px!important;">Acciones</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($informe_ventas as $informe_venta)
                <tr>
                    <td><center>
                        <form id = "form{{$informe_venta->id}}" class = "form-table" action="{{ route('informe_ventas.destroy', $informe_venta->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a class="btn2 btn-info" title = "Detalle" href="{{ route('informe_ventas.show',['id'=>$informe_venta->id]) }}"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('informe_ventas.edit',['id'=>$informe_venta->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            @if(Auth::user()->validar_permiso('dia_delete'))
                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar({{$informe_venta->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                            @endif
                        </form></center>
                    </td>
                    <td>{{ $informe_venta->tipodoc }}</td>
                    <td>{{ $informe_venta->numdoc }}</td>
                    @if(is_numeric($informe_venta->vrunit))
                        <td>${{number_format($informe_venta->vrunit, 1, ',', '.')}}</td>
                    @else
                        <td>{{ $informe_venta->vrunit }}</td>
                    @endif
                    <td>{{ $informe_venta->identificacion }}</td>
                    <td>{{ $informe_venta->nombre }}</td>
                    
                    @if(is_numeric($informe_venta->vrunit))
                        <td>${{number_format($informe_venta->vrtotal, 1, ',', '.')}}</td>
                    @else
                        <td>{{ $informe_venta->vrtotal }}</td>
                    @endif
                    <td>{{ $informe_venta->lugar }}</td>
                    <td>{{ $informe_venta->fecha }}</td>
                    <td>{{ $informe_venta->cantidad }}</td>
                    <td>{{ $informe_venta->descripcion }}</td>                    
                    <td>
                        @if($informe_venta->urlimagen != null && $informe_venta->urlimagen != '')
                        <a class="btn2 btn-dark" target = "_blank" href="{{ asset('uploads/archivos') }}/{{$informe_venta->urlimagen}}" class = "bold"><i class = "fa fa-search"></i></a>
                        @endif
                    </td>

                    <td><center>
                    <form id = "form{{$informe_venta->id}}" class = "form-table" action="{{ route('informe_ventas.destroy', $informe_venta->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a class="btn2 btn-info" title = "Detalle" href="{{ route('informe_ventas.show',['id'=>$informe_venta->id]) }}"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('informe_ventas.edit',['id'=>$informe_venta->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            @if(Auth::user()->validar_permiso('dia_delete'))
                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar({{$informe_venta->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                            @endif
                        </form></center>
                    </td>
                </tr>
                @endforeach
            </tbody>


            <!--    FIN CONTENIDO TABLA   -->

        </table>
        {{$informe_ventas->links()}}
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
                        valor = $('#buscar_paginacion').val();
                        url = "{{ $url_paginacion }}";
                        url = url + "?search="+valor;
                        $(location).attr('href',url);
                    }
                });
			});
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
		</script>
@endsection







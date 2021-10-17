@extends('layout')

@section('content')
<div class="app-title">
    <div>
        <h1>
            {{ $title }}
            <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo</a>

        </h1>
    </div>
</div>

<div class = "row">




<div class="col-md-12">
    <div class="tile">
    <div class="tile-body">
    <div class="row">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Buscar" id = "buscar_paginacion" value = "{{$valor}}">
        </div>
    </div>
    <br>
    @if(session()->has('mensaje'))
        <div class="alert alert-success">
            {!! session()->get('mensaje') !!}
        </div>
    @endif
    @if ($cotizaciones->isNotEmpty())
    <div class = "table-responsive" >
        <table style = "min-width:800px!important;" class="table table-hover table-bordered" id="sampleTable">
            

            <!--    COMIENZA CONTENIDO TABLA   -->
            <thead>
                <tr>
                    <th >ID</th>
                    <th style = 'width: 400px;'>Detalle</th>
                    <th >Agencia</th>
                    <th style = 'width: 250px;'>Tipo Gasto</th>
                    <th >No. Documento</th>
                    <th >Valor Autorizado</th>
                    <th >Estado Autorización</th>
                    <th >Estado Gasto</th>
                    <th >Estado Revisoría</th>
                    <th >Usuario</th>
                    <th >Fecha</th>
                    <th style = "min-width:100px!important;">Acciones</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($cotizaciones as $cotizacion)
                <tr>
                    <td>{{ $cotizacion->id }}</td>
                    <td>{{ strtoupper($cotizacion->descripcion) }}</td>
                    <td>{{ $cotizacion->agencia != null ? $cotizacion->agencia->agennom : "" }}</td>
                    <td title = "# DOCUMENTO :: {{$cotizacion->num_gasto}}">{{ $cotizacion->tipo_gasto != null ? $cotizacion->tipo_gasto->tipo." :: ".$cotizacion->tipo_gasto->nombre : "" }}</td>
                    <td>{{ $cotizacion->num_gasto }}</td>
                    <td>${{number_format($cotizacion->valor_autorizado($cotizacion->id), 1, ',', '.')}}</td>
                    <td>
                        <span class = 'span-estilo' style = "background: {{$cotizacion->estado != null ? $cotizacion->estado->color : ''}};">{{ $cotizacion->estado != null ? $cotizacion->estado->nombre : "" }}</span>
                    </td>
                    <td>
                        <span class = 'span-estilo' style = "background: {{$cotizacion->gasto_estado != null ? $cotizacion->gasto_estado->color : ''}};">{{ $cotizacion->gasto_estado != null ? $cotizacion->gasto_estado->nombre : "" }}</span>
                    </td>
                    <td>
                        <span class = 'span-estilo' style = "background: {{$cotizacion->revisoria_estado != null ? $cotizacion->revisoria_estado->color : ''}};">{{ $cotizacion->revisoria_estado != null ? $cotizacion->revisoria_estado->nombre : "" }}</span>
                    </td>
                    <td title = "{{$cotizacion->usuario_nombre($cotizacion->user_new)}}">{{ $cotizacion->user_new }}</td>
                    <td>{{ $cotizacion->created_at }}</td>

                    <td><center>
                    <form id = "form{{$cotizacion->id}}" class = "form-table" action="{{ route('cotizaciones.destroy', $cotizacion->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            @if(Auth::user()->validar_permiso('cot_show'))    
                            <a class="btn2 btn-info" title = "Detalle" href="{{ route('cotizaciones.show',['id'=>$cotizacion->id]) }}"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                            @endif
                            @if($cotizacion->estado_id != 3 || Auth::user()->validar_permiso('cot_edit_anul'))
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('cotizaciones.edit',['id'=>$cotizacion->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            @endif
                            @if(Auth::user()->validar_permiso('cot_anular_cot'))                            
                            <a class="btn2 btn-danger"  title = "Anular" href="" onclick="eliminar({{$cotizacion->id}},event)"><i class="fa green fa-eraser" aria-hidden="true"></i></a>
                            @endif
                        </form></center>
                    </td>
                </tr>
                @endforeach
            </tbody>


            <!--    FIN CONTENIDO TABLA   -->

        </table>
        {{$cotizaciones->links()}}
        @if(session()->has('mensaje'))
            <div class="alert alert-success">
                {!! session()->get('mensaje') !!}
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
                if($("#cerrar").val() == 'close'){
                    window.close();
                }

                $(".btn-consultar").click(function(event){
                    event.preventDefault();
                    id = $(this).attr("href");
                    $("#id").val(id);
                    $("#cotizacion-form").submit();
                })

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
                    message: "Está seguro que desea anular el registro?",
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







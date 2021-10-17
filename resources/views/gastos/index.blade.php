@extends('layout')

@section('content')
<div class="app-title">
    <div>
        <h1>
            {{ $title }}

        </h1>
    </div>
</div>

<div class = "row">




<div class="col-md-12">
    <div class="tile">
    <div class="tile-body">
    @if ($gastos->isNotEmpty())
    <div class = "table-responsive">
        <table style = "min-width:800px!important;" class="table table-hover table-bordered" id="sampleTable">
            

            <!--    COMIENZA CONTENIDO TABLA   -->
            <thead>
                <tr>
                    <th >ID</th>
                    <th >Detalle Gasto</th>
                    <th >Agencia</th>
                    <th >Tipo Gasto</th>
                    <th >Estado</th>
                    <th >Valor Solicitado</th>
                    <th >Valor Autorizado</th>
                    <th >Usuario</th>
                    <th >Fecha</th>
                    <th style = "min-width:80px!important;">Acciones</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($gastos as $gasto)
                <tr>
                    <td>{{ $gasto->id }}</td>
                    <td>{{ $gasto->descripcion }}</td>
                    <td>{{ $gasto->agencia != null ? $gasto->agencia->agennom : "" }}</td>
                    <td>{{ $gasto->tipo_gasto != null ? $gasto->tipo_gasto->tipo.' :: '.$gasto->tipo_gasto->nombre : "" }}</td>
                    <td>
                        <span class = 'span-estilo' style = "background: {{$gasto->estado != null ? $gasto->estado->color : ''}};">{{ $gasto->estado != null ? $gasto->estado->nombre : "" }}</span>
                    </td>
                    <td>${{number_format($gasto->valor_solicitado, 1, ',', '.')}}</td>
                    <td>${{number_format($gasto->valor_autorizado, 1, ',', '.')}}</td>
                    <td title = "{{$gasto->usuario_nombre($gasto->user_new)}}">{{ $gasto->user_new }}</td>
                    <td>{{ $gasto->created_at }}</td>


                    <td><center>
                    <form id = "form{{$gasto->id}}" class = "form-table" action="{{ route('gastos.destroy', $gasto->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            @if(Auth::user()->validar_permiso('gast_show'))    
                            <a class="btn2 btn-info" title = "Detalle" href="{{ route('gastos.show',['id'=>$gasto->id]) }}"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                            @endif
                            @if($gasto->estado_id != 3 || Auth::user()->validar_permiso('gast_edit_anul'))
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('gastos.edit',['id'=>$gasto->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            @endif
                            @if(Auth::user()->validar_permiso('gast_anular_gast'))
                            <a class="btn2 btn-danger"  title = "Anular" href="" onclick="eliminar({{$gasto->id}},event)"><i class="fa green fa-eraser" aria-hidden="true"></i></a>
                            @endif
                        </form></center>
                    </td>
                </tr>
                @endforeach
            </tbody>


            <!--    FIN CONTENIDO TABLA   -->

        </table>
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
                if($("#cerrar").val() == 'close'){
                    window.close();
                }
                
                $('#sampleTable').dataTable( {
                    "order": [],
                    "iDisplayLength": 25,
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
                $(".btn-consultar").click(function(event){
                    event.preventDefault();
                    id = $(this).attr("href");
                    $("#id").val(id);
                    $("#gasto-form").submit();
                })
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







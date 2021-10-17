@extends('layout')

@section('content')
<div class="app-title">
    <div>
        <h1>
            {{ $title }}
            <a href="{{ route('gasto_estados.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo</a>

        </h1>
    </div>
</div>

<div class = "row">




<div class="col-md-9">
    <div class="tile">
    <div class="tile-body">
    @if ($gasto_estados->isNotEmpty())
    <div class = "table-responsive">
        <table class="table table-hover table-bordered" id="sampleTable">
            

            <!--    COMIENZA CONTENIDO TABLA   -->
            <thead>
                <tr>
                    <th >ID</th>
                    <th >Estado</th>
                    <th >Color</th>
                    <th style = "width:140px!important;">Acciones</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($gasto_estados as $gasto_estado)
                <tr>
                    <td>{{ $gasto_estado->id }}</td>
                    <td>{{ $gasto_estado->nombre }}</td>
                    <td>
                        <span class = 'span-estilo' style = "background: {{$gasto_estado->color}};">{{ $gasto_estado->color }}</span>
                    </td>
                    <td><center>
                    <form id = "form{{$gasto_estado->id}}" class = "form-table" action="{{ route('gasto_estados.destroy', $gasto_estado->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('gasto_estados.edit',['id'=>$gasto_estado->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar({{$gasto_estado->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
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
                    $("#gasto_estado-form").submit();
                })
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







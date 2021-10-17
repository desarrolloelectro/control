@extends('layout')

@section('content')
<div class="app-title">
    <div>
        <h1>
            {{ $title }}
            <a href="{{ route('tipo_documentos.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo</a>

        </h1>
    </div>
</div>

<div class = "row">




<div class="col-md-9">
    <div class="tile">
    <div class="tile-body">
    @if ($tipo_documentos->isNotEmpty())
    <div class = "table-responsive">
        <table class="table table-hover table-bordered" id="sampleTable">
            

            <!--    COMIENZA CONTENIDO TABLA   -->
            <thead>
                <tr>
                    <th >ID</th>
                    <th >codigo</th>
                    <th >Tipo Documento</th>
                    <th >CODCIU</th>
                    <th >CIUDAD</th>
                    <th >CODDPTO</th>
                    <th >DEPTO</th>

                    <th style = "width:140px!important;">Acciones</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($tipo_documentos as $tipo_documento)
                <tr>
                    <td>{{ $tipo_documento->id }}</td>
                    <td>{{ $tipo_documento->codigo }}</td>
                    <td>{{ $tipo_documento->nombre }}</td>
                    <td>{{ $tipo_documento->codciu }}</td>
                    <td>{{ $tipo_documento->ciudad }}</td>
                    <td>{{ $tipo_documento->coddpto }}</td>
                    <td>{{ $tipo_documento->depto }}</td>
                    <td><center>
                    <form id = "form{{$tipo_documento->id}}" class = "form-table" action="{{ route('tipo_documentos.destroy', $tipo_documento->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('tipo_documentos.edit',['id'=>$tipo_documento->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar({{$tipo_documento->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
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
                    $("#tipo_documento-form").submit();
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







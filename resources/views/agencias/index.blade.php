@extends('layout')

@section('content')
<div class="app-title">
    <div>
        <h1>
            {{ $title }}
            <a href="{{ route('agencias.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nueva agencia</a>

        </h1>
    </div>
</div>

<div class = "row">




<div class="col-md-9">
    <div class="tile">
    <div class="tile-body">
    @if ($agencias->isNotEmpty())
    <div class = "table-responsive">
        <table class="table table-hover table-bordered" id="sampleTable">
            

            <!--    COMIENZA CONTENIDO TABLA   -->
            <thead>
                <tr>
                    <th >Código</th>
                    <th >Agencia</th>
                    <th >Sucursal</th>
                    <th >Regional</th>
                    <th >Pertenece</th>
                    <th >Estado</th>
                    <th style = "width:140px!important;">Acciones</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($agencias as $agencia)
                <tr>
                    <td>{{ $agencia->codagen }}</td>
                    <td>{{ $agencia->agennom }}</td>
                    <td>{{ $agencia->agensucur }}</td>
                    <td>{{ $agencia->agenreg }}</td>
                    <td>{{ $agencia->agenpertenece }}</td>
                    <td>{{ $agencia->activo == '1' ? 'ACTIVO' : 'INACTIVO' }}</td>
                    <td><center>
                    <form id = "form{{$agencia->codagen}}" class = "form-table" action="{{ route('agencias.destroy', $agencia->codagen) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('agencias.edit',['id'=>$agencia->codagen]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
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
        <p>No hay agencias registrados.</p>
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
                    $("#codagen").val(id);
                    $("#agencia-form").submit();
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







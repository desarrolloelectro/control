@extends('layout')

@section('content')
<div class="app-title">
    <div>
        <h1>
            {{ $titulo }}
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo Usuario</a>

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
    </div>
    <br>
    @if ($usuarios->isNotEmpty())
    <div class = "table-responsive">
        <table class="table table-hover table-bordered" id="sampleTable">
            

            <!--    COMIENZA CONTENIDO TABLA   -->

            <thead>
                <tr>
                    <th >Usuario</th>
                    <th >Cedula</th>
                    <th >Nombre</th>
                    <th >Teléfono</th>
                    <th >Nivel Control</th>
                    <th >Agencia</th>
                    <th style = "width:140px!important;">Acciones</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($usuarios as $usu)
                <tr>
                    <td>{{ $usu->coduser }}</td>
                    <td>{{ $usu->cedula }}</td>
                    <td>{{ $usu->nombre }}</td>
                    <td>{{ $usu->telefono }}</td>
                    <td>{{ $usu->nivel_mostrar($usu->nivel_control) }}</td>
                    <td>{{ $usu->agencia }} :: {{ $usu->agencia_detalle($usu->agencia) }}</td>
                    <td>
                    <form id = "form{{$usu->coduser}}" class = "form-table" action="{{ route('usuarios.destroy', $usu->coduser) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <center>
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('usuarios.edit',['id'=>$usu->coduser]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            </center>                 
                        
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

            <!--    FIN CONTENIDO TABLA   -->

        </table>
        {{$usuarios->links()}}
        @if(session()->has('mensaje'))
            <div class="alert alert-success">
                {{ session()->get('mensaje') }}
            </div>
        @endif
    </div>
    @if(session()->has('alerta'))
        <div class="alert alert-danger">
            {{ session()->get('alerta') }}
        </div>
    @endif
    @else
        <p>No hay usuarios registrados.</p>
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






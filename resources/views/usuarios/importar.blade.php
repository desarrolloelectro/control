@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->


<!-- AQUI CIERRAN LAS VARIABLES  -->


<div class="app-title">
    <div>
        <h1>{{ $titulo }}</h1>
    </div>
</div>

<div class = "row">
    <div class="col-md-9">
          <div class="tile">
            <div class="tile-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h6>Para continuar debe corregir los siguientes errores:</h6>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- AQUI INICIA EL FORMULARIO  -->
            <form method="POST" id = "formulario" action="{{ $accion }}" files="true" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ $metodo }}
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>Seleccione archivo</label><br>
                            <input required type="file" name="archivo" id="archivo" class = "form-control"/>
                        </div>      
                    </div> 
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                            <div class = "alert alert-warning">Recuerde que el archivo seleccionado debe tener extensión <strong>.xlsx</strong></div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                            <div class = "alert alert-info">El encabezado para importar el archivo .xlsx debe ser:
                                <br><br>
                                <div class = "table-responsive">
                                    <table class = "table table-bordered" style = "background:white!important;">
                                        <tr>
                                            <td>IDENTIFICACION</td>
                                            <td>NOMBRE</td>
                                            <td>USUARIO</td>
                                            <td>TELEFONO</td>
                                            <td>CORREO</td>
                                            <td>AGENCIA</td>
                                        </tr>
                                    </table>
                                </div>
                              </div>
                    </div>
                </div>
                
                
                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {!! session()->get('mensaje') !!}
                    </div>
                @endif
                @if(session()->has('alerta'))
                    <div class="alert alert-danger">
                        {!! session()->get('alerta') !!}
                    </div>
                @endif
                
                <button type="submit" id = "btn-enviar" class="btn btn-primary"><i class="fa fa-cloud-upload" aria-hidden="true"></i> {{$boton}}</button>
                <span  style = "display:none;" id = "alert-busqueda">
                    <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
                    Cargando...
                </span>
            </form>
            <!-- AQUI CIERRA EL FORMULARIO  -->


            </div>
          </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($) {
        $('input[type="text"],textarea').on('blur', function () { 
            var currVal = $(this).val();
            $(this).val(currVal.toUpperCase());
        });
        
        $("#formulario").submit(function(){
            $("#alert-busqueda").show();
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







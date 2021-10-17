@extends('layout')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<!-- AQUI INICIAN LAS VARIABLES  -->

<?php
if(isset($formulario)){
    $id = $formulario->id;
    $nombre = $formulario->nombre;
    $descripcion = $formulario->descripcion;
    $estado = $formulario->estado;

}else{
    $id ="";
    $nombre ="";
    $descripcion = "";
    $estado = "";

}
?>
<!-- AQUI CIERRAN LAS VARIABLES  -->


<div class="app-title">
    <div>
        <h1>{{ $title }}</h1>
    </div>
</div>

<div class = "row">
    <div class="col-md-12">
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
            <form method="POST" id = "formulario" action="{{ $accion }}">
                    {{ csrf_field() }}
                    {{ $metodo }}
                    <div class = "row">
                        <div class = "col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre Formulario</label>
                                <input required type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre',$nombre) }}">
                            </div>
                        </div>
                        <div class = "col-md-6">
                            <div class="form-group">
                                <label for="nombre">Descripción</label>
                                <input type="text" class="form-control" name="descripcion" id="descripcion" value="{{ old('descripcion',$descripcion) }}">
                            </div>
                        </div>
                    </div>
                    @isset($formulario)
                    <div class = "row">
                        <div class = "col-md-6">
                            <div class="form-group">
                                <label for="nombre">Estado</label>
                                <select name="estado" id="estado" class = "form-control">
                                    <option value="1" <?php if($estado == 1) echo "selected"; ?> >ACTIVO</option>
                                    <option value="0" <?php if($estado == 0) echo "selected"; ?> >INACTIVO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endisset
                    <h3 class="h5-gray">AGREGAR PREGUNTAS</h3>

                    <div class = "row">
                        <div class = "col-md-3">
                            <div class="form-group">
                                <label>Tipo Pregunta</label>
                                <select class="form-control" name="tipo_pregunta_add" id="tipo_pregunta_add">
                                    <option value="" disabled selected>SELECCIONE</option>
                                    <option value="1">SI / NO</option>
                                    <option value="2">ABIERTA</option>
                                    <option value="3">NUMÉRICA</option>
                                    <option value="4">FECHA</option>
                                </select>
                            </div>
                        </div>
                        <div class = "col-md-9">
                            <div class="form-group">
                                <label>Pregunta </label>
                                <input type="text" class="form-control" name="pregunta_add" id="pregunta_add">
                            </div>
                        </div>
                    </div>
                    <div class = "row">
                        <div class = "col-md-6">
                                <button type="button" id = "btn-agregar" class="btn btn-info" onclick = "agregar();">Agregar</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="table-responsive">
                            <table id = "tabla_acompanantes" class="table table-list table-striped table-bordered">
                                <thead style = "font-weight:bold!important;">
                                    <tr>
                                        <th style = "width:180px;">TIPO PREGUNTA</th>
                                        <th>PREGUNTA</th>
                                        <th style = "width:50px;">ACCIONES</th>
                                    </tr>
                                </thead> 
                            <tbody>
                            @isset($formulario_detalle)
                                @if ($formulario_detalle->isNotEmpty())
                                    @foreach($formulario_detalle as $index => $detalle)
                                    <tr>
                                        <td> 
                                            <input type="hidden" name = "id_tabla[]" id = "id_tabla[]" value = "{{ $detalle->id }}">
                                            <select class="form-control" name="tipo_tabla[]">
                                                <option value="1" <?php if($detalle->tipo_pregunta == 1) echo "selected"; ?> >SI / NO</option>
                                                <option value="2" <?php if($detalle->tipo_pregunta == 2) echo "selected"; ?>>ABIERTA</option>
                                                <option value="3" <?php if($detalle->tipo_pregunta == 3) echo "selected"; ?>>NUMÉRICA</option>
                                                <option value="4" <?php if($detalle->tipo_pregunta == 4) echo "selected"; ?>>FECHA</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class = "form-control" name = "pregunta_tabla[]" id = "pregunta_tabla[]" value = "{{ $detalle->pregunta }}">
                                        </td>
                                        <td>
                                            <center>
                                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar_pregunta({{$detalle->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                                            <a class="btn2 btn-info"  title = "Mover" style = "color:white"><i class="fa fa-arrows" aria-hidden="true"></i></a>
                                            </center>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            @endisset
                                
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>



                
                    @if(session()->has('mensaje'))
                        <div class="alert alert-success">
                            {{ session()->get('mensaje') }}
                        </div>
                    @endif

                    <div class="alert alert-danger" id = "alert-error" style = "display:none;">
                        
                    </div>
                    
                    <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                    <a href="{{ route('formularios.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
                    <span  style = "display:none;" id = "alert-busqueda">
                        Cargando...
                        <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
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
            //var currVal = $(this).val();
            //$(this).val(currVal.toUpperCase());
        });

        $("#formulario").submit(function(){
            $("#alert-busqueda").show();
        })

        $(document).on('click', '.btn-delete', function (event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });

        $('tbody').sortable();
        
    });

    function eliminar_pregunta(id,event){
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

                    //eliminar pregunta
                    $("#alert-busqueda").show();
                    $.ajax({
                    url: "{{ route('formularios.eliminar_pregunta') }}",
                    method: 'POST',
                    data: {id:id, "_token": "{{ csrf_token() }}"},
                    success: function(data) {
                        if(data.status == 'success'){
                            location.reload();
                        }else{
                            $("#alert-error").html(data.mensaje);
                            $("#alert-error").show();
                        }
                    }
                });

                }
            }
        });
    }


    function agregar(){
        tipo_pregunta = $("#tipo_pregunta_add").val();
        pregunta = $("#pregunta_add").val();

        if(tipo_pregunta == "" || pregunta == ""){
            alert("¡Todos los campos son obligatorios!");
        }else{
            cadena = "<tr>";

            select = "";
            selected1 = "";
            selected2 = "";
            selected3 = "";
            selected4 = "";

            if(tipo_pregunta == 1){selected1 = 'selected';}
            if(tipo_pregunta == 2){selected2 = 'selected';}
            if(tipo_pregunta == 3){selected3 = 'selected';}
            if(tipo_pregunta == 4){selected4 = 'selected';}


            select +="<select class='form-control' name='tipo_pregunta[]'>";
            select +="<option value='1' "+selected1+">SI / NO</option>";
            select +="<option value='2' "+selected2+">ABIERTA</option>";
            select +="<option value='3' "+selected3+">NUMÉRICA</option>";
            select +="<option value='4' "+selected4+">FECHA</option>";
            select +="</select>";

            cadena += "<td>"+select+"</td>";
            cadena += "<td><input class = 'form-control' type='text' name='pregunta[]' value='"+pregunta+"' /></td>";
            cadena +=  "<td><button type='button' class = 'btn-delete' ><i class='fa fa-times-circle red bigger-24' aria-hidden='true'></i></button></td>";
            cadena += "</tr>";

            $("#tabla_acompanantes tbody").append(cadena);
            $("#pregunta").val("");
        }
    }
    

</script>
@endsection



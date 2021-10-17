@extends('layout')

@section('content')

<?php
        if(isset($empresa)){
            $titulo = $empresa->titulo;
            $subtitulo = $empresa->subtitulo;
            $descripcion = $empresa->descripcion;
            $nomenlace = $empresa->nomenlace;
            $linkenlace = $empresa->linkenlace;
            $nomimagen = $empresa->nomimagen;
            $urlimagen = $empresa->urlimagen;
        }else{
            $titulo = "";
            $subtitulo = "";
            $descripcion = "";
            $nomenlace = "";
            $linkenlace = "";
            $nomimagen = "";
            $urlimagen = "";
        }
    ?>




<div class="app-title">
    <div>
        <h1>{{ $title }}</h1>
    </div>
</div>

<div class = "row">
    <div class="col-md-9">
          <div class="tile">
            <h3 class="tile-title">{{ $title }}</h3>
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

            <form method="POST" id = "formulario" action="{{ $accion }}" files="true" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ $metodo }}
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>NIT <li class = "fa fa-asterisk red icono-asterisk"></li></label>
                            <input required type="text" class="form-control" name="titulo" id="titulo" value="{{ old('titulo',$titulo) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Razón social <li class = "fa fa-asterisk red icono-asterisk"></li></label>
                            <input required type="text" class="form-control" name="subtitulo" id="subtitulo" value="{{ old('subtitulo',$subtitulo) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" value="{{ old('descripcion',$descripcion) }}">
                        </div>
                    </div> 
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" name="nomenlace" id="nomenlace" value="{{ old('nomenlace',$nomenlace) }}">
                        </div>
                    </div>   
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Whatsapp</label>
                            <input type="text" class="form-control" name="linkenlace" id="linkenlace" value="{{ old('linkenlace',$linkenlace) }}">
                        </div>       
                    </div> 
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Correo electrónico</label>
                            <input type="text" class="form-control" name="nomimagen" id="nomimagen" value="{{ old('nomimagen',$nomimagen) }}">
                        </div>       
                    </div> 
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="upload-btn-wrapper form-group">
                            <label>Imagen</label><br>
                            <input type="file" class = "form-control" name="urlimagen" id="urlimagen" />
                        </div>      
                    </div> 
                </div>
                <span  style = "display:none;" id = "alert-busqueda">
                    Cargando...
                    <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
                </span>

                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                <div class="tile-footer">
                    <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                    <a href="{{ route('institucion.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
                </div>
            </form>
            </div>
          </div>
    </div>
    @if($urlimagen != "")
    <div class = "col-md-3">
            <div class="tile">
                    <h3 class="tile-title">Imagen cargada</h3>
                    <div class="tile-body">
                            <div class="form-group">
                                <img style = "width:100%;" src="{{ asset('uploads/') }}{{"/".$urlimagen}}" class = "img-responsive">
                            </div>           
                    </div>
            </div>   
        </div>
    @endif

</div>



<script type="text/javascript">
    jQuery(function($) {
        $("#formulario").submit(function(){
            $("#alert-busqueda").show();
        })

        $('#btn_imagen').click(function(event){
            event.preventDefault();
            $('#urlimagen').click();
        })

        $('#urlimagen').change(function() {
            if($("#urlimagen").val() != ''){
                var file = $('#urlimagen')[0].files[0].name;
                $("#btn_imagen").html("Seleccionado");
            }else{
                $("#btn_imagen").html("Seleccionar");
            }
        });

        $('#btn_archivo').click(function(event){
            event.preventDefault();
            $('#urlarchivo').click();
        })

        $('#urlarchivo').change(function() {
            if($("#urlarchivo").val() != ''){
                var file = $('#urlarchivo')[0].files[0].name;
                $("#btn_archivo").html("Seleccionado");
            }else{
                $("#btn_archivo").html("Seleccionar");
            }
        });
    });
    

</script>
@endsection

@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($iva_estado)){
    $nombre = $iva_estado->nombre;
    $color = $iva_estado->color;
    $tipo = $iva_estado->tipo;

}else{
    $nombre ="";
    $color ="";
    $tipo ="";

}
?>

<!-- AQUI CIERRAN LAS VARIABLES  -->


<div class="app-title">
    <div>
        <h1>{{ $titulo }}</h1>
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
                @isset($opcion)
                <input type="hidden" id = "opcion" name = "opcion" value = "{{$opcion}}">
                @endisset
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Estado</label>
                            <input required type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre',$nombre) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Color</label>
                            <input required type="text" class="form-control" name="color" id="color" value="{{ old('color',$color) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="tipo" id="tipo" class = 'form-control'>
                                <option value="1" {{old('tipo',$tipo) == '1' ? 'SELECTED' : ''}}  >ESTADO CAJAS 1</option>
                                <option value="2" {{old('tipo',$tipo) == '2' ? 'SELECTED' : ''}}  >ESTADO BANCOS</option>
                                <option value="3" {{old('tipo',$tipo) == '3' ? 'SELECTED' : ''}}  >ESTADO CAJAS 2</option>
                            </select>
                        </div>
                    </div>
                </div>
                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                <a href="{{ route('iva_estados.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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
        $("#formulario").submit(function(){
            $("#alert-busqueda").show();
        })
    });
    

</script>
@endsection



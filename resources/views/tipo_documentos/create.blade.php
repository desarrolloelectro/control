@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($tipo_documento)){
    $codigo = $tipo_documento->codigo;
    $nombre = $tipo_documento->nombre;
    $codciu = $tipo_documento->codciu;
    $ciudad = $tipo_documento->ciudad;
    $coddpto = $tipo_documento->coddpto;
    $depto = $tipo_documento->depto;

}else{
    $codigo ="";
    $nombre ="";
    $codciu ="";
    $ciudad ="";
    $coddpto ="";
    $depto ="";

}
?>

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
            <form method="POST" id = "formulario" action="{{ $accion }}">
                {{ csrf_field() }}
                {{ $metodo }}
                @isset($opcion)
                <input type="hidden" id = "opcion" name = "opcion" value = "{{$opcion}}">
                @endisset
                <div class = "row">
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>Código</label>
                            <input required type="text" class="form-control" name="codigo" id="codigo" value="{{ old('codigo',$codigo) }}">
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>Tipo Documento</label>
                            <input required type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre',$nombre) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>CODCIU</label>
                            <input required type="text" class="form-control" name="codciu" id="codciu" value="{{ old('codciu',$codciu) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>CIUDAD</label>
                            <input required type="text" class="form-control" name="ciudad" id="ciudad" value="{{ old('ciudad',$ciudad) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>CODDPTO</label>
                            <input required type="text" class="form-control" name="coddpto" id="coddpto" value="{{ old('coddpto',$coddpto) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>DEPTO</label>
                            <input required type="text" class="form-control" name="depto" id="depto" value="{{ old('depto',$depto) }}">
                        </div>
                    </div>
                </div>
                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                <a href="{{ route('tipo_documentos.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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



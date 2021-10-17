@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($banco)){
    $nombre = $banco->nombre;
    $tipo_pago_id = $banco->tipo_pago_id;
    $num_cuenta = $banco->num_cuenta;
    $tipo_cuenta_id = $banco->tipo_cuenta_id;

}else{
    $nombre ="";
    $tipo_pago_id ="";
    $num_cuenta ="";
    $tipo_cuenta_id ="";

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
                            <label>Tipo Pago</label>
                            <select class="form-control" name="tipo_pago_id" id="tipo_pago_id" required aria-required="true">
                                @foreach($tipo_pagos as $tipo_pago)
                                <option value="{{$tipo_pago->id}}" {{ old('tipo_pago_id') == $tipo_pago->id ? 'selected' : ''}}>{{$tipo_pago->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>Banco</label>
                            <input required type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre',$nombre) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>Tipo Cuenta</label>
                            <select name="tipo_cuenta_id" id="tipo_cuenta_id" class = "form-control">
                                <option value="1" <?php if($tipo_cuenta_id == '1') echo 'selected'; ?> >AHORROS</option>
                                <option value="2" <?php if($tipo_cuenta_id == '2') echo 'selected'; ?> >CORRIENTE</option>
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>No. Cuenta</label>
                            <input required type="text" class="form-control" name="num_cuenta" id="num_cuenta" value="{{ old('num_cuenta',$num_cuenta) }}">
                        </div>
                    </div>
                </div>
                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                <a href="{{ route('bancos.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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



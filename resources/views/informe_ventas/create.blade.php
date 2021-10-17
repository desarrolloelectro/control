@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($informe_venta)){
    $tipoid = $informe_venta->tipoid;
    $identificacion = $informe_venta->identificacion;
    $nombre = $informe_venta->nombre;
    $tipofac = $informe_venta->tipofac;
    $tipodoc = $informe_venta->tipodoc;
    $numdoc = $informe_venta->numdoc;
    $lugar = $informe_venta->lugar;
    $fecha = $informe_venta->fecha;
    $categoria = $informe_venta->categoria;
    $genero = $informe_venta->genero;
    $cantidad = $informe_venta->cantidad;
    $unidad = $informe_venta->unidad;
    $descripcion = $informe_venta->descripcion;
    $vrunit = $informe_venta->vrunit;
    $vrtotal = $informe_venta->vrtotal;
    $mediopago = $informe_venta->mediopago;
    $numsoporte = $informe_venta->numsoporte;
    $fechaentrega = $informe_venta->fechaentrega;
    $pvppublico = $informe_venta->pvppublico;
    $urlimagen = $informe_venta->urlimagen;
    $obs = $informe_venta->obs;

}else{
    $nombre ="";
    $urlimagen ="";

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
            <form method="POST" id = "formulario" action="{{ $accion }}" files="true" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ $metodo }}
                @isset($opcion)
                <input type="hidden" id = "opcion" name = "opcion" value = "{{$opcion}}">
                @endisset
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>TIPOID</label>
                            <input required readonly type="text" class="form-control" name="tipoid" id="tipoid" value="{{ old('tipoid',$tipoid) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>IDENTIFICACION</label>
                            <input required readonly type="text" class="form-control" name="identificacion" id="identificacion" value="{{ old('identificacion',$identificacion) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>NOMBRE</label>
                            <input required readonly type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre',$nombre) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>TIPOFAC</label>
                            <input required readonly type="text" class="form-control" name="tipofac" id="tipofac" value="{{ old('tipofac',$tipofac) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>TIPODOC</label>
                            <input required readonly type="text" class="form-control" name="tipodoc" id="tipodoc" value="{{ old('tipodoc',$tipodoc) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>NUMDOC</label>
                            <input required readonly type="text" class="form-control" name="numdoc" id="numdoc" value="{{ old('numdoc',$numdoc) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>LUGAR</label>
                            <input required readonly type="text" class="form-control" name="lugar" id="lugar" value="{{ old('lugar',$lugar) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>FECHA</label>
                            <input required readonly type="text" class="form-control" name="fecha" id="fecha" value="{{ old('fecha',$fecha) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>CATEGORIA</label>
                            <input required readonly type="text" class="form-control" name="categoria" id="categoria" value="{{ old('categoria',$categoria) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>GENERO</label>
                            <input required readonly type="text" class="form-control" name="genero" id="genero" value="{{ old('genero',$genero) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>CANTIDAD</label>
                            <input required readonly type="text" class="form-control" name="cantidad" id="cantidad" value="{{ old('cantidad',$cantidad) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>UNIDAD</label>
                            <input required readonly type="text" class="form-control" name="unidad" id="unidad" value="{{ old('unidad',$unidad) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>DESCRIPCION</label>
                            <input required readonly type="text" class="form-control" name="descripcion" id="descripcion" value="{{ old('descripcion',$descripcion) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>VRUNIT</label>
                            <input required readonly type="text" class="form-control" name="vrunit" id="vrunit" value="{{ old('vrunit',$vrunit) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>VRTOTAL</label>
                            <input required readonly type="text" class="form-control" name="vrtotal" id="vrtotal" value="{{ old('vrtotal',$vrtotal) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>MEDIOPAGO</label>
                            <select name="mediopago" id="mediopago" class = 'form-control'>
                                <option value="0" selected>NO DEFINE</option>
                                @foreach($medio_pagos as $medio_pago)
                                <option value="{{$medio_pago->id}}" {{ old('mediopago',$mediopago) == $medio_pago->id ? 'selected' : ''}}>{{$medio_pago->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>NUMSOPORTE</label>
                            <input type="text" class="form-control" name="numsoporte" id="numsoporte" value="{{ old('numsoporte',$numsoporte) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>FECHAENTREGA</label>
                            <input required readonly type="text" class="form-control" name="fechaentrega" id="fechaentrega" value="{{ old('fechaentrega',$fechaentrega) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>PVPPUBLICO</label>
                            <input required readonly type="text" class="form-control" name="pvppublico" id="pvppublico" value="{{ old('pvppublico',$pvppublico) }}">
                        </div>
                    </div>
                    <div class = "col-md-3">
                        <div class="form-group">
                            <label>Archivo</label>
                            <input type="file" class="form-control" name="urlimagen" id="urlimagen" value="{{ old('urlimagen',$urlimagen) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label>OBSERVACIONES</label>
                            <textarea name="obs" id="obs"  rows="3" class = 'form-control'>{{ old('obs',$obs) }}</textarea>
                        </div>
                    </div>
                </div>
                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                <a href="{{ route('informe_ventas.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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



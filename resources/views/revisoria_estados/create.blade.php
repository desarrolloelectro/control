@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($revisoria_estado)){
    $nombre = $revisoria_estado->nombre;
    $color = $revisoria_estado->color;
    $bloquear_perfiles = $revisoria_estado->bloquear_perfiles;

}else{
    $nombre ="";
    $color ="";
    $bloquear_perfiles ="";

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
                            <label>Bloquear</label>
                            <select class = 'form-control' name="bloquear_perfiles" id="bloquear_perfiles">
                                <option value="0" <?php if($bloquear_perfiles == '0') echo 'selected'; ?> >NO</option>
                                <option value="1" <?php if($bloquear_perfiles == '1') echo 'selected'; ?>>SI</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--<h3 class="h5-dark"> Perfiles Bloqueados</h3>
                <div class = "row">
                    @if ($roles->isNotEmpty())
                        @foreach($roles as $rol)
                            <div class="col-md-12" style="margin-bottom:5px!important;">
                                <li class="list-group-item"> 
                                    <input type="checkbox" <?php if(in_array($rol->id,$perfiles_bloqueados)) echo "checked"; ?> name = "perfiles_bloqueados[]" value="{{$rol->id}}"> {{$rol->nombre}}
                                </li>
                            </div>
                        @endforeach
                    @endif
                    
                </div>
                <br>-->

                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                <a href="{{ route('revisoria_estados.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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



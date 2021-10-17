@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($tipo_gasto)){
    $nombre = $tipo_gasto->nombre;
    $tipo = $tipo_gasto->tipo;

}else{
    $nombre ="";
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
                            <label>Tipo Gasto</label>
                            <input required type="text" class="form-control" name="tipo" id="tipo" value="{{ old('tipo',$tipo) }}">
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input required type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre',$nombre) }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="h5-dark">Regionales </h3>
                        <div class="bs-component">
                            <ul class="nav nav-pills">
                                @if ($regionales->isNotEmpty())
                                    @foreach($regionales as $regional)
                                    @if ($loop->first)
                                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#{{$regional->agenreg}}">{{$regional->agenreg}}</a></li>
                                    @else
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#{{$regional->agenreg}}">{{$regional->agenreg}}</a></li>
                                    @endif
                                    @endforeach
                                @endif
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                @if ($regionales->isNotEmpty())
                                    @foreach($regionales as $index => $regional)
                                    <div class="tab-pane fade <?php if($index == 0) echo 'active show';?>" style="padding:10px;" id="{{$regional->agenreg}}">
                                        <div class="row">
                                            @if ($agencias->isNotEmpty())
                                                @foreach($agencias as $agencia)
                                                    @if($agencia->agenreg == $regional->agenreg)
                                                        <div class="col-md-12" style="margin-bottom:5px!important;">
                                                            <li class="list-group-item"> 
                                                                <input type="checkbox" <?php if(in_array($agencia->codagen,$lista_agencias)) echo "checked"; ?> name = "agencias[]" value="{{$agencia->codagen}}"> {{$agencia->agennom}}
                                                            </li>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                                

                                
                            </div>
                        </div>
                    </div>
                </div>

                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                <a href="{{ route('tipo_gastos.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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



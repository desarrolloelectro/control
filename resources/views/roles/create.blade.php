@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->

<?php
if(isset($rol)){
    $nombre_rol = $rol->nombre;
    $agencias = $rol->agencias;
}else{
    $nombre_rol ="";
    $agencias ="";
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
                <div class = "row">
                    <div class = "col-md-12">
                        <div class="form-group">
                            <label >Perfil</label>
                            <input required type="text" class="form-control" name="nombre_rol" id="nombre_rol" value="{{ old('nombre_rol',$nombre_rol) }}">
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="h5-dark">Módulos </h3>
                        <div class="bs-component">
                            <ul class="nav nav-pills">
                                
                                
                                @if ($modulos->isNotEmpty())
                                    @foreach($modulos as $modulo)
                                    @if ($loop->first)
                                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#{{$modulo->id}}">{{$modulo->nombre}}</a></li>
                                    @else
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#{{$modulo->id}}">{{$modulo->nombre}}</a></li>
                                    @endif
                                    @endforeach
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#agencias">RESTRINGIR AGENCIAS</a></li>

                                @endif
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                @if ($modulos->isNotEmpty())
                                    @foreach($modulos as $index => $modulo)
                                    <div class="tab-pane fade <?php if($index == 0) echo 'active show';?>" style="padding:10px;" id="{{$modulo->id}}">
                                        <div class="row">
                                            @if ($permisos->isNotEmpty())
                                                @foreach($permisos as $permiso)
                                                    @if($permiso->modulo_id == $modulo->id)
                                                        @if($permiso->grupo == '2')
                                                        <div class="col-md-12" style="margin-bottom:5px!important;">
                                                            <li class="list-group-item" style="background:#bbdbf15c!important;font-weight:bold!important;"> 
                                                                {{$permiso->nombre}}
                                                            </li>
                                                        </div>
                                                        @else
                                                        <div class="col-md-12" style="margin-bottom:5px!important;">
                                                            <li class="list-group-item"> 
                                                                <input type="checkbox" <?php if(in_array($permiso->codigo,$roles_permisos)) echo "checked"; ?> name = "permisos[]" value="{{$permiso->codigo}}"> {{$permiso->nombre}}
                                                            </li>
                                                        </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                @endif

                                <div class="tab-pane fade" style="padding:10px;" id="agencias">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-bottom:5px!important;">
                                            <li class="list-group-item" style="background:#bbdbf15c!important;font-weight:bold!important;"> 
                                                Ingrese códigos de agencias separadas por coma (,). Solamente aplica para permiso LISTAR COMPLETO
                                            </li>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="margin-bottom:5px!important;">
                                            <li class="list-group-item"> 
                                                <input type="text" class = 'form-control' name = 'agencias' value="{{ old('agencias',$agencias) }}">
                                            </li>
                                        </div>
                                    </div>
                                </div>
                                

                                
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
                <a href="{{ route('roles.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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







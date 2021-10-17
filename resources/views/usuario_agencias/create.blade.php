@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->

<?php
if(isset($usuario_agencia)){
    $usuario_id = $usuario_agencia->usuario_id;
}else{
    $usuario_id ="";
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
                            <label >Usuario</label>
                            @isset($usuario_agencia)
                            <input type="text" readonly class = "form-control" value = "{{ $usuario_agencia->usuario != null ? $usuario_agencia->usuario->nombre : ''}}">
                            @else
                            <select class="form-control" name="usuario_id" id="usuario_id" required aria-required="true">
                                @foreach($usuarios as $usuario)
                                <option value="{{$usuario->coduser}}" {{ old('usuario_id') == $usuario->coduser ? 'selected' : ''}}>{{$usuario->coduser}} :: {{$usuario->nombre}}</option>
                                @endforeach
                            </select>
                            @endisset
                            
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
                <a href="{{ route('usuario_agencias.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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
        $("#usuario_id").select2();
    });
    

</script>
@endsection







@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php
if(isset($user)){
    $coduser = $user->coduser;
    $cedula = $user->cedula;
    $nombre = $user->nombre;
    $correo = $user->correo;
    $telefono = $user->telefono;
    $agencia = $user->agencia;
    $contrasena = "";
    $nivel_control = $user->nivel_control;
    $useractivo = $user->useractivo;

}else{
    $coduser ="";
    $cedula ="";
    $nombre = "";
    $correo = "";
    $telefono = "";
    $contrasena = "";
    $nivel_control = "";
    $agencia = "";
    $useractivo = "";

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
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label >Usuario <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            
                            @isset($user)
                            <input type="text" readonly  required class="form-control" name="coduser" id="coduser" value="{{ old('coduser',$coduser) }}">
                            @else
                            <input type="text" required class="form-control" name="coduser" id="coduser" value="{{ old('coduser',$coduser) }}">
                            @endisset
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label >Cédula <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input required type="text" class="form-control" name="cedula" id="cedula" value="{{ old('cedula',$cedula) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label >Nombre <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input required type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre',$nombre) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label >Correo</label>
                            <input type="text" class="form-control" name="correo" id="correo" value="{{ old('correo',$correo) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label >Teléfono</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" value="{{ old('telefono',$telefono) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label >Contraseña <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <input <?php if(!isset($user)){echo "required";} ?>  type="password" class="form-control" name="contrasena" id="contrasena" value="{{ old('contrasena',$contrasena) }}">
                        </div>
                    </div>
                </div>
                <div class = "row">
                    @isset($perfil)
                    @else
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Perfil Toolset Control <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select class="form-control" name="nivel_control" id="nivel_control" aria-required="" aria-required="true" required>
                                <option value="" selected disabled>SELECCIONE</option>
                                @foreach($roles as $rol)
                                <option value="{{$rol->id}}" {{ old('nivel_control', $nivel_control) == $rol->id ? 'selected' : ''}}>{{$rol->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label>Agencia <span style="font-size:10px;color:red;"><i class="fa fa-asterisk"></i></span></label>
                            <select class="form-control" name="agencia" id="agencia" aria-required="" aria-required="true" required>
                                <option value="" selected disabled>SELECCIONE</option>
                                @foreach($agencias as $agen)
                                <option value="{{$agen->codagen}}" {{ old('agencia', $agencia) == $agen->codagen ? 'selected' : ''}}>{{$agen->agennom}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endisset
                </div>
                
                @if(session()->has('mensaje'))
                    <div class="alert alert-success">
                        {{ session()->get('mensaje') }}
                    </div>
                @endif
                @if(session()->has('alerta'))
                    <div class="alert alert-danger">
                        {{ session()->get('alerta') }}
                    </div>
                @endif
                
                <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                <a href="{{ route('usuarios.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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





@extends('layout')

@section('content')

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
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>Fecha Inicial</label>
                            <input type="date" class="form-control" name="fecha1" id="fecha1" value="{{ old('fecha1',$fecha1) }}">
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class="form-group">
                            <label>Fecha Final</label>
                            <input type="date" class="form-control" name="fecha2" id="fecha2" value="{{ old('fecha2',$fecha2) }}">
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
                    @if(session()->has('alerta'))
                        <div class="alert alert-danger">
                            {{ session()->get('alerta') }}
                        </div>
                    @endif
                    <div class="tile-footer">
                        <button type="submit" id = "btn-enviar" class="btn btn-primary">{{$boton}}</button>
                        <a href="{{ route('reportes.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
                    </div>
            </form>
            </div>
          </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($) {
        $("#tipo_gasto").submit(function(){
            //$("#alert-busqueda").show();
        })
        $("#agencia_id").select2();
        $("#tipo_gasto_id").select2();
    });
</script>
@endsection


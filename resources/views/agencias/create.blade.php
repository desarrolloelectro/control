@extends('layout')

@section('content')


<!-- AQUI INICIAN LAS VARIABLES  -->
<?php

if(isset($_GET['opc'])){
    $opcion = $_GET['opc'];
}else{
    $opcion = "";
}

if(isset($agencia)){
    $codagen = $agencia->codagen;
    $agennom = $agencia->agennom;
    $agensucur = $agencia->agensucur;
    $agenreg = $agencia->agenreg;
    $agenpertenece = $agencia->agenpertenece;
    $activo = $agencia->activo;

}else{
    $codagen ="";
    $agennom ="";
    $agensucur ="";
    $agenreg ="";
    $agenpertenece ="";
    $activo ="";
}
?>

<!-- AQUI CIERRAN LAS VARIABLES  -->


<div class="app-title">
    <div>
        <h1><i class="fa fa-dashboard"></i> {{ $titulo }}</h1>
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
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label for="codagen">Código Agencia</label>
                            <input required <?php if(isset($agencia)) echo 'readonly'; ?> type="number" max= '999999' class="form-control" name="codagen" id="codagen" value="{{ old('codagen',$codagen) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label for="agennom">Nombre Agencia</label>
                            <input required type="text" class="form-control" name="agennom" id="agennom" value="{{ old('agennom',$agennom) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label for="agensucur">Sucursal</label>
                            <select name="agensucur" id="agensucur" class="form-control" required aria-required="true">
                                <option value="" selected disabled>SELECCIONE</option>
                                <option value="08" <?php if($agensucur == '08') echo "selected";?> >JL & RB S.A.S.</option>
                                <option value="09" <?php if($agensucur == '09') echo "selected";?>>KEEWAY BENELLI COLOMBIA S.A.S.</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label for="agenreg">Regional</label>
                            <select name="agenreg" id="agenreg" class="form-control" required aria-required="true">
                                <option value="" selected disabled>SELECCIONE</option>
                                <option value="NAR" <?php if($agenreg == 'NAR') echo "selected";?> >NARIÑO</option>
                                <option value="CAU" <?php if($agenreg == 'CAU') echo "selected";?>>CAUCA</option>
                                <option value="PUT" <?php if($agenreg == 'PUT') echo "selected";?>>PUTUMAYO</option>
                                <option value="VAL" <?php if($agenreg == 'VAL') echo "selected";?>>VALLE</option>
                                <option value="BOG" <?php if($agenreg == 'BOG') echo "selected";?>>BOGOTÁ</option>
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label for="agenpertenece">Pertenece</label>
                            <input required type="number" class="form-control" max= '999999' name="agenpertenece" id="agenpertenece" value="{{ old('agenpertenece',$agenpertenece) }}">
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="form-group">
                            <label for="activo">ESTADO</label>
                            <select name="activo" id="activo" class="form-control" required aria-required="true">
                                <option value="1" <?php if($activo == '1') echo "selected";?> >ACTIVO</option>
                                <option value="0" <?php if($activo == '0') echo "selected";?>>INACTIVO</option>
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
                <a href="{{ route('agencias.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
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



@if(!empty($tipo_gastos))
  <option value="" required disabled>SELECCIONE</option>
  @foreach($tipo_gastos as $tipo_gasto)
    <?php
      $lista_agencias = explode(",",$tipo_gasto->agencias);
    ?>
    @if(in_array($agencia_id,$lista_agencias))
    <option value="{{$tipo_gasto->id}}">{{$tipo_gasto->tipo}} :: {{$tipo_gasto->nombre}}</option>
    @endif
  @endforeach
@endif


@isset($bancos)
<option value="0" selected>NO DEFINE</option>
@if(!empty($bancos))
  @foreach($bancos as $banco)
    <option value="{{$banco->id}}">{{$banco->nombre}} :: {{$banco->num_cuenta}} :: {{$banco->tipo_pago != null ? $banco->tipo_pago->nombre : ''}}</option>
  @endforeach
@endif
@endisset
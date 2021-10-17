<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
        <title>COTIZACIÓN TOOLSET CONTROL</title>
    </head>
    <body>
        <center>
            <table width="800" border="0" style = "border:1px solid #ddd;padding:5px;border-radius:5px;box-shadow: 0 1px 0 1px #d2d2d2!important;">
                <tbody>
                    <tr >
                        <td style = "padding:20px;"><center><img width="200" src="http://control.keewaycolombia.co/uploads/logo2.png"></center></td>
                    </tr>
                    <tr>
                        <td><hr color="#dc3545" size=3></td>
                    </tr>
                    <tr>
                        <td style = "padding-left:30px;padding-right:30px;">
                                                     

                            <h3 class="h5-dark"> Detalle Usuario</h3>
                            <div class = "table-responsive">
                                    <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                                        <tr>
                                            <td class="bold">Nombre</td>
                                            <td>
                                                <input readonly required type="text" style = 'width:95%;' value="{{ $usuario != null ? $usuario->nombre : '' }}">
                                            </td>
                                            <td class="bold">Celular</td>
                                            <td>
                                                <input readonly required type="text" style = 'width:95%;' value="{{  $usuario != null ? $usuario->telefono : '' }}">

                                            </td>
                                            <td class="bold">Doc. Vinculado</td>
                                            <td>
                                                <input readonly required type="text" style = 'width:95%;' value="{{  $usuario != null ? $usuario->cedula : ''}}">  
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold">Fecha</td>
                                            <td>
                                                <input readonly required type="text" style = 'width:95%;' value="{{ $cotizacion->created_at }}">
                                            </td>
                                            <td class="bold">Agencia</td>
                                            <td>
                                                <input readonly required type="text" style = 'width:95%;' value="{{ $usuario != null ? $usuario->agencia_detalle($usuario->agencia) : ''}}">
                                            </td>
                                        </tr>
                                    </table>
                            </div>
                            <h3 class="h5-dark"> Detalle Cotización # {{$cotizacion->id}}</h3>
                            <div class = "table-responsive">
                                    <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                                        <tr>
                                            <td class="bold">Agencia</td>
                                            <td>
                                                <input readonly required type="text" style = 'width:95%;' value="{{ $cotizacion->agencia != null ? $cotizacion->agencia->agennom : '' }}">
                                            </td>
                                            <td class="bold">Tipo Gasto</td>
                                            <td colspan = '2'>
                                                <input readonly required type="text" style = 'width:95%;' value="{{ $cotizacion->tipo_gasto != null ? $cotizacion->tipo_gasto->tipo.' :: '.$cotizacion->tipo_gasto->nombre : '' }}">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold">Detalle</td>
                                            <td colspan = '3'>
                                                <input readonly required type="text" style = 'width:95%;' value="{{  $cotizacion->descripcion}}">  
                                            </td>   
                                        </tr>                                            
                                        
                                    </table>
                            </div>
                            <h3 class="h5-dark"> Cotizaciones / Autorizaciones Adjuntas</h3>
                            <div class = "table-responsive">
                                    <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                                        <thead style = "font-weight:bold!important;">
                                            <tr>
                                                <th>NOMBRE ARCHIVO</th>
                                                <th>VALOR COTIZACIÓN</th>
                                                <th><center>AUTORIZADO</center></th>
                                            </tr>
                                        </thead>    
                                        <tbody>
                                            @isset($cotizacion_detalle)
                                                @if ($cotizacion_detalle->isNotEmpty())
                                                    @foreach($cotizacion_detalle as $index => $detalle)
                                                    <tr>
                                                        <td> 
                                                            <input readonly style = 'width:95%;' type="text" value = "{{ $detalle->nombre }}">
                                                        </td>
                                                        <td>
                                                            <input readonly style = 'width:95%;' type="text" value = "${{number_format($detalle->valor, 1, ',', '.')}}">
                                                        </td>
                                                        <td>
                                                            <center>
                                                                    <span><?php if($detalle->autorizado == 1) {echo "SI";}else{echo 'NO';} ?></span>
                                                            </center>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            @endisset
                                        </tbody>                                       
                                    </table>
                            </div>
                            <h3 class="h5-dark"> Imagenes / Fotos</h3>
                            <div class = "table-responsive">
                                    <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                                        <tbody>

                                        @isset($cotizacion_detalle)
                                            @if ($cotizacion_detalle->isNotEmpty())
                                                @foreach($cotizacion_detalle as $index => $detalle)
                                                
                                                <tr >
                                                    <td style = "padding:20px;"><center><img width="500" src="{{ asset('uploads/cotizaciones') }}/{{$detalle->urlarchivo}}"></center></td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        @endisset
                                        </tbody>                                       
                                        
                                    </table>
                            </div>

                            <h3 class="h5-dark"> Observaciones / Estado</h3>
                            <div class = "table-responsive">
                                    <table class="table table-list table-striped table-bordered" style = "min-width: 800px!important;">
                                        <tr>
                                            <td class="bold">Observaciones Auditoría</td>
                                            <td>
                                                <input readonly required type="text" style = 'width:95%;' value="{{ $cotizacion->obs }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold">Estado Cotización</td>
                                            <td colspan = '3'>
                                                <input readonly required type="text" style = 'width:95%;' value="{{ $cotizacion->estado != null ? $cotizacion->estado->nombre : '' }}">  
                                            </td>   
                                        </tr>                                            
                                        
                                    </table>
                                </div>
                            </div>



                            
                            <p>Atentamente,</p>
                            <p style = "margin:0;padding:0;">{{Auth::user()->nombre}}</p>
                            <p style = "margin:0;padding:0;">{{Auth::user()->telefono}}</p>
                            <br>


                        </td>
                    </tr>
                </tbody>
            </table>
        </center>
    </body>
</html>



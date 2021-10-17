@isset($cotizaciones)
    @if ($cotizaciones->isNotEmpty())
    <div class = "table-responsive">
        <table class="table table-hover table-bordered " id="sampleTable">
            

            <!--    COMIENZA CONTENIDO TABLA   -->
            <thead>
                <tr>
                    <th >ID</th>
                    <th >Detalle Cotización</th>
                    <th >Agencia</th>
                    <th >Tipo Gasto</th>
                    <th >Estado</th>
                    <th >Valor Autorizado</th>
                    <th >Fecha</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($cotizaciones as $cotizacion)
                    @if(!in_array($cotizacion->id,$contenido))
                    <tr ondblclick= "agregar_cotizacion('{{$cotizacion->id}}', event)">

                        <td>{{ $cotizacion->id }}</td>
                        <td>{{ $cotizacion->descripcion }}</td>
                        <td>{{ $cotizacion->agencia != null ? $cotizacion->agencia->agennom : "" }}</td>
                        <td>{{ $cotizacion->tipo_gasto != null ? $cotizacion->tipo_gasto->tipo.' :: '.$cotizacion->tipo_gasto->nombre : "" }}</td>
                        <td>
                            <span class = 'span-estilo' style = "background: {{$cotizacion->estado != null ? $cotizacion->estado->color : ''}};">{{ $cotizacion->estado != null ? $cotizacion->estado->nombre : "" }}</span>
                        </td>
                        <td>${{number_format($cotizacion->valor_autorizado($cotizacion->id), 1, ',', '.')}}</td>
                        <td>{{ $cotizacion->created_at }}</td>

                    </tr>
                    @endif
                    
                @endforeach
                </tbody>


            <!--    FIN CONTENIDO TABLA   -->

        </table>
        @if(session()->has('mensaje'))
            <div class="alert alert-success">
                {{ session()->get('mensaje') }}
            </div>
        @endif
    </div>
    @else
        <p>¡No se encontraron registros!</p>
    @endif
@endisset


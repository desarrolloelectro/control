@extends('layout')

@section('content')
<div class="app-title">
    <div>
        <h1>
            {{ $title }}
            <a href="{{ route('dia_ivas.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo</a>
            @if(Auth::user()->validar_permiso('dia_rep_nov'))
            <a href="{{ route('reportes.dia_ivas_export') }}" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i>Descargar Informe</a>
                @if(2 == 1)
                <a href="{{ route('dia_ivas.importar') }}" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i>Subir Archivo</a>
                @endif
            @endif
        </h1>
    </div>
</div>

<div class = "row">




<div class="col-md-12">
    <div class="tile">
    <div class="tile-body">
    <div class="row">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Buscar" id = "buscar_paginacion" value = "{{$valor}}">
        </div>
        <div class = 'col-md-4'>
            <select name="medio_pago_id" id="medio_pago_id" class = 'form-control' required>
                <option value="0" selected>TODOS LOS MEDIOS DE PAGO</option>
                @foreach($medio_pagos as $medio_pago)
                <option value="{{$medio_pago->id}}" {{ old('medio_pago_id',$medio_pago_id) == $medio_pago->id ? 'selected' : ''}}>{{$medio_pago->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" readonly class="form-control" value = "TOTAL REGISTROS: {{$dia_ivas->total()}}">
        </div>
        
    </div>
    <br>
    @if ($dia_ivas->isNotEmpty())
    <div class = "table-responsive">
        <table class="table table-hover table-bordered" id="sampleTable">
            

            <!--    COMIENZA CONTENIDO TABLA   -->
            <thead>
                <tr>
                    <th >ID</th>
                    <th >tipodoc</th>
                    <th >numdoc</th>
                    <th >Identificación</th>
                    <th >Nombre</th>
                    <th >Departamento</th>
                    <th >Ciudad</th>
                    <th >Cantidad</th>
                    <th >vrunit</th>
                    <th >vrtotal</th>
                    <th style = "min-width:80px!important;">fecha</th>
                    <th >Descripción</th>
                    <th >Medio Pago</th>
                    <th >Estado Caja</th>
                    <th >Estado Bancos</th>
                    <th >Estado Caja 2</th>
                    <th >Archivo</th>
                    <th style = "min-width:90px!important;">Acciones</th>
                </tr>
                </thead> 
                <tbody>
                @foreach($dia_ivas as $dia_iva)
                <tr>
                    <td>{{ $dia_iva->id }}</td>
                    <td>{{ $dia_iva->tipo_documento != null ? $dia_iva->tipo_documento->codigo : 'NO DEFINE' }}</td>
                    <td>{{ $dia_iva->numdoc }}</td>                    
                    <td>{{ $dia_iva->identificacion }}</td>
                    <td>{{ $dia_iva->nombre }}</td>
                    <td>{{ $dia_iva->tipo_documento != null ? $dia_iva->tipo_documento->depto : 'NO DEFINE' }}</td>
                    <td>{{ $dia_iva->tipo_documento != null ? $dia_iva->tipo_documento->ciudad : 'NO DEFINE' }}</td>
                    <td>{{ $dia_iva->cantidad }}</td>
                    @if(is_numeric($dia_iva->vrunit))
                        <td>${{number_format($dia_iva->vrunit, 1, ',', '.')}}</td>
                    @else
                        <td>{{ $dia_iva->vrunit }}</td>
                    @endif
                    @if(is_numeric($dia_iva->vrtotal))
                        <td>${{number_format($dia_iva->vrtotal, 1, ',', '.')}}</td>
                    @else
                        <td>{{ $dia_iva->vrtotal }}</td>
                    @endif
                    <td>{{ $dia_iva->fecha }}</td>
                    <td>{{ $dia_iva->descripcion }}</td>                    
                    <td>{{ $dia_iva->medio_pago != null ? $dia_iva->medio_pago->nombre : 'NO DEFINE' }}</td>
                    <td>
                        <span class = 'span-estilo' style = "background: {{$dia_iva->iva_estado != null ? $dia_iva->iva_estado->color : ''}};">{{ $dia_iva->iva_estado != null ? $dia_iva->iva_estado->nombre : "" }}</span>
                    </td>      
                    <td>
                        <span class = 'span-estilo' style = "background: {{$dia_iva->banco_estado != null ? $dia_iva->banco_estado->color : ''}};">{{ $dia_iva->banco_estado != null ? $dia_iva->banco_estado->nombre : "" }}</span>
                    </td>    
                    <td>
                        <span class = 'span-estilo' style = "background: {{$dia_iva->caja2_estado != null ? $dia_iva->caja2_estado->color : ''}};">{{ $dia_iva->caja2_estado != null ? $dia_iva->caja2_estado->nombre : "" }}</span>
                    </td>               
                    <td>
                        @if($dia_iva->urlimagen != null && $dia_iva->urlimagen != '')
                        <a class="btn2 btn-dark" target = "_blank" href="{{ asset('uploads/archivos') }}/{{$dia_iva->urlimagen}}" class = "bold"><i class = "fa fa-search"></i></a>
                        @endif
                    </td>

                    <td>
                        <center>
                        <form id = "form{{$dia_iva->id}}" class = "form-table" action="{{ route('dia_ivas.destroy', $dia_iva->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a class="btn2 btn-info" title = "Detalle" href="{{ route('dia_ivas.show',['id'=>$dia_iva->id]) }}"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                            <a class="btn2 btn-success" title = "Modificar" href="{{ route('dia_ivas.edit',['id'=>$dia_iva->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                            @if(Auth::user()->validar_permiso('dia_delete_nov'))
                            <a class="btn2 btn-danger"  title = "Eliminar" href="" onclick="eliminar({{$dia_iva->id}},event)"><i class="fa green fa-times-circle" aria-hidden="true"></i></a>
                            @endif
                        </form>
                        </center>
                    </td>
                </tr>
                @endforeach
            </tbody>


            <!--    FIN CONTENIDO TABLA   -->

        </table>
        {{$dia_ivas->links()}}
        @if(session()->has('mensaje'))
            <div class="alert alert-success">
                {{ session()->get('mensaje') }}
            </div>
        @endif
        @if(session()->has('opcion'))
                    <input type="hidden" id = "cerrar" value = "{{ session()->get('opcion') }}">
        @endif
    </div>
    @if(session()->has('alerta'))
        <div class="alert alert-danger">
            {{ session()->get('alerta') }}
        </div>
    @endif
    @else
        <p>No se contraron registros.</p>
    @endif
    <span  style = "display:none;" id = "alert-busqueda">
        Cargando...
        <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
    </span>
    <div class="tile-footer">
    </div>
    
    </div>
    </div>
</div>

</div>


		<script type="text/javascript">
			jQuery(function($) {
                $('#buscar_paginacion').keypress(function(event){
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if(keycode == '13'){
                        buscar_filtro();
                    }
                });
                $("#medio_pago_id").change(function(){
                    buscar_filtro();
                });
			});

            function buscar_filtro(){
                valor = $('#buscar_paginacion').val();
                medio_pago_id = $('#medio_pago_id').val();
                url = "{{ $url_paginacion }}";
                url = url + "?search="+valor+"&medio_pago_id="+medio_pago_id;
                $(location).attr('href',url);
            }
            
            function eliminar(id,event){
                event.preventDefault();
                bootbox.confirm({
                    message: "Está seguro que desea eliminar el registro?",
                    buttons: {
                        confirm: {
                            label: '<i class="fa fa-check"></i> Confirmar',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancelar',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result == true){
                            $("#alert-busqueda").show();
                            $("#form"+id).submit();
                        }
                    }
                });
            }
		</script>
@endsection







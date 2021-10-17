@extends('layout')
@section('content')

<div class="app-title">
    <div>
        <center>
            <h1>
                {{ $title }}            
            </h1>
        </center>
    </div>
</div>

<div class = "row">
<div class="col-md-12">
    <div class="tile">
    <div class="tile-body">
    <div class="row">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Buscar por cambios, Responsable o Fecha" id = "buscar_paginacion" value = "{{ $valor }}">
        </div>
        <div class='col-md-4' >
        </div>
        <div class="col-md-4">

        </div>
    </div>
    <br>
    @if (!empty($dsi))
    <div class = "table-responsive">
        <table class="table table-hover table-bordered table-striped" id="sampleTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cambios</th>
                    <th>Responsable</th>
                    <th>Fecha</th>
                </tr>
            </thead> 
            <tbody>
                @foreach($histories as $history)
                @php 
                    $audits = explode("|",$history->audit);
                @endphp
                <tr>
                    <td>{{ $history->id }}</td>
                    <td>
                        <ul style="
                            width: 42%;
                            word-break: break-word;
                        ">
                            @foreach($audits as $audit)
                            <li>{{ $audit }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $history->user }}</td>
                    <td>{{ $history->date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$histories->links()}}
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
        url = url + "?search="+valor;
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

    function restaurar(id,event,value=''){
        event.preventDefault();
        bootbox.confirm({
            message: "Está seguro que desea restaurar el registro?",
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
                    $("#form"+id).attr('action', $("#restore"+id).attr('action'));
                    $("#form"+id).submit();
                }
            }
        });
    }
</script>
@endsection
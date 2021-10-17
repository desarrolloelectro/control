@extends('layout')

@section('content')

<div class="app-title">
    <div>
        <h1>{{ $title }}</h1>
    </div>
</div>




<div class = "row">
    <div class="col-md-12">
          <div class="tile">
                <div class="tile-body">
                






                    <div class = 'row'>

                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('agencias.index') }}" >
                                <div class="widget-small primary  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Agencias</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('areas.index') }}" >
                                <div class="widget-small success  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Áreas</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('bancos.index') }}" >
                                <div class="widget-small info  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Bancos</h4>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('categorias.index') }}" >
                                <div class="widget-small danger  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Categorías</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('empresas.index') }}" >
                                <div class="widget-small primary  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Empresas</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('envio_correos.index') }}" >
                                <div class="widget-small success  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Envío Correos</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('cotizacion_estados.index') }}" >
                                <div class="widget-small info  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Estados Cotización</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('gasto_estados.index') }}" >
                                <div class="widget-small danger  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Estados Gasto</h4>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('revisoria_estados.index') }}" >
                                <div class="widget-small primary  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Estados Revisoría</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('generos.index') }}" >
                                <div class="widget-small success  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Generos</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('iva_estados.index') }}" >
                                <div class="widget-small info  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>IVA Estados</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('medio_pagos.index') }}" >
                                <div class="widget-small danger  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Medios de Pago</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('roles.index') }}" >
                                <div class="widget-small primary  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Perfiles</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('tipo_gastos.index') }}" >
                                <div class="widget-small success  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Tipo Gastos</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('tipo_identificaciones.index') }}" >
                                <div class="widget-small info  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Tipo Identificaciones</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('tipo_pagos.index') }}" >
                                <div class="widget-small danger  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Tipo Pagos</h4>
                                    </div>
                                </div>
                            </a>
                        </div>





                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('tipo_facturas.index') }}" >
                                <div class="widget-small primary  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Tipo Facturas</h4>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('tipo_documentos.index') }}" >
                                <div class="widget-small success  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Tipo Documentos</h4>
                                    </div>
                                </div>
                            </a>
                        </div>





                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('unidades.index') }}" >
                                <div class="widget-small info  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Unidades</h4>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('usuarios.index') }}" >
                                <div class="widget-small danger  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Usuarios</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="{{ route('usuario_agencias.index') }}" >
                                <div class="widget-small primary  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Usuario Agencias</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
          </div>
    </div>
</div>




@endsection



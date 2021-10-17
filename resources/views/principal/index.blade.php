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

                



                    <div class = "row">

                        <div class="col-md-3 col-lg-3 col-configuracion">

                            <a href="{{ route('cotizaciones.index') }}" >

                                <div class="widget-small danger coloured-icon"><i class="icon fa fa-check-square-o fa-3x"></i>

                                    <div class="info">

                                    <h4>Autorizaciones / Gastos</h4>

                                    </div>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-3 col-lg-3 col-configuracion">

                            <a href="{{ route('reportes.index') }}" >

                                <div class="widget-small primary coloured-icon"><i class="icon fa fa-bar-chart fa-3x"></i>

                                    <div class="info">

                                    <h4>Reportes</h4>

                                    </div>

                                </div>

                            </a>

                        </div>

                        <div class="col-md-3 col-lg-3 col-configuracion">

                        <a href="{{ route('dsi.index') }}" >

                            <div class="widget-small primary coloured-icon"><i class="icon fa fa-shopping-bag fa-3x"></i>

                                <div class="info">

                                <h4>Días sin IVA</h4>

                                </div>

                            </div>

                        </a>

                        </div>

                        <div class="col-md-3 col-lg-3 col-configuracion">

                            <a href="{{ route('institucion.index') }}" >

                                <div class="widget-small info coloured-icon"><i class="icon fa fa-cogs fa-3x"></i>

                                    <div class="info">

                                    <h4>Configuración</h4>

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




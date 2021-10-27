@extends('layout')

@section('content')

<div class="app-title">
    <div>
        <h1>{!! $title !!}</h1>
    </div>
</div>

<div class = "row">
    <div class="col-md-12">
          <div class="tile">
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="#" >
                                <div class="widget-small primary  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Tipo de Recibo</h4>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-lg-3 col-configuracion">
                            <a href="#" >
                                <div class="widget-small success  coloured-icon"><i class="icon fa fa-cog fa-3x"></i>
                                    <div class="info">
                                    <h4>Tipo de Documentos</h4>
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
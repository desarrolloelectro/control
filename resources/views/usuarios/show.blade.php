@extends('layout')

@section('content')

    <?php
        $id = $documento->id;
        $nombre = $documento->nombre;
        $descripcion = $documento->descripcion;
    ?>

<div class = "panel panel-headline">
        <div class = "panel-heading">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <h3 class="pb-1">{{$titulo}}</h3>
            </div>  
        </div>
        <div class = "panel-body">
            <form>
<div class = "row">
    <div class = "col-md-3">
        <div class="form-group">
            <label >ID</label>
            <input readonly type="text" class="form-control" value="{{ $id }}">
        </div>
    </div>
    <div class = "col-md-3">
        <div class="form-group">
            <label >Nombre</label>
            <input readonly type="text" class="form-control" value="{{ $nombre }}">
        </div>
    </div>
    <div class = "col-md-3">
        <div class="form-group">
            <label >Descripción</label>
            <input readonly type="text" class="form-control" value="{{ $descripcion }}">
        </div>
    </div>
</div>
<a href="{{ route('documentos.index') }}"><button class="btn btn-success" type = "button">Regresar</button></a>
            </form>
        </div>
</div>
@endsection
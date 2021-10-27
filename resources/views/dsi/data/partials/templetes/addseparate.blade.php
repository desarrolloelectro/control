@php
if(isset($dia_iva)){
    $permiso_revisoria = \App\DsiPermission::dsi_permiso($dia_iva->dsi_id,'dsi.data.revisoria');
}else{
    $permiso_revisoria = "dsi_developer";
}
@endphp
<div class="col-md-12" style="display: block; text-align: right;">
        @if($ayuda)<button data-toggle="modal" data-target="#helpSeparate" class="close" style="float:left" type="button" title="Ayuda">?</button>@endif
        @if(!isset($dia_iva))
        <button title="Quitar este documento separado" class="close" type="button" onclick="removeItemPanel(this.parentNode)">x</button>
        @else
            @if(Auth::user()->validar_permiso('dsi_developer'))
            <button title="Quitar este documento separado de la lista" class="close" onclick="alert('candicato a eliminar dsi_data_advances')" type="button">x</button>
            @else
            <button title="No se puede quitar este documento separado" class="close" type="button" disabled>x</button>
            @endif
        @endif    
        </div>
        <input hidden {{ isset($dia_iva) ? ' readonly ' : '' }} type="number" class="dsi_ant_dsm_id form-control" name="dsi_ant_dsm_id[]"  value="{{ isset($dia_iva) && isset($dsi_data_dsms) ? $dsi_data_dsms->id : '' }}">
        <h3 style="margin: -6px -5px 6px -5px; border-radius: 11px 11px 0 0;" class="h5-dark">Separado</h3>
    <div class="col-md-12">
        <div class="form-group" class="divseparado">       
            <div class="row">
                <fieldset style="padding: 0px;" class="col-12 col-md-12">
                    <div class="row">
                        <div class="col-md-4" style="display: block;">
                            <div class="form-group">
                                <label>Documento Separado mercancía</label> <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                                <input required="" required-marked="marked" {{ isset($dia_iva) ? '' : '' }} readonly type="text" class="dsi_ant_dsm form-control" name="dsi_ant_dsm[]" value="{{ isset($dia_iva) && isset($dsi_data_dsms) ? $dsi_data_dsms->dsm : $documentdsm }}">           
                            </div>
                        </div>
                        <div class="col-md-4" style="display: block;">
                            <div class="form-group">
                                <label>Número de documento</label> <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                                <input {{ isset($dsi_data_dsms) && $dsi_data_dsms->revisoria_manager==1 ?  " disabled " : "" }} {{ isset($dia_iva) ? '' : '' }} type="number" class="dsi_ant_num_dsm form-control" name="dsi_ant_num_dsm[]" value="{{ isset($dia_iva) && isset($dsi_data_dsms) ? $dsi_data_dsms->num_dsm : '' }}">            
                            </div>
                        </div>
                        
                        @if(isset($dia_iva) && isset($dsi_data_dsms))
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <br>
                                        <input type="hidden" name="dsm_revisoria_manager[{{ $dsi_data_dsms->id }}]" value="0">
                                        <input type="checkbox" {{ (Auth::user()->validar_permiso($permiso_revisoria)) ? "" : " disabled " }} {{ isset($dsi_data_dsms) && $dsi_data_dsms->revisoria_manager==1 ?  " checked " : "" }} name="dsm_revisoria_manager[{{ $dsi_data_dsms->id }}]" value="1">
                                        Revisado en Manager
                                    </label>   
                                </div>
                            </div>
                            
                        @endif
                    </div>
                    <h3 class="h5-dark">Productos</h3>
                    <div>
                        <div class="col-md-12 products"><?php
                        if(isset($dia_iva)){
                            //$dsi_data_dsms->dsi_data_products();
                            if(!empty($dsi_data_dsms->dsi_data_products)){
                                foreach($dsi_data_dsms->dsi_data_products as $id => $dsi_data_products){ ?>
                        <div class="row productItem" ondblclick="list_products_with_advances(this)">
                        @include('dsi.data.partials.templetes.addproduct',['editar_datos' => $editar_datos,'ayuda' => $ayuda, 'dsi_data_products' => $dsi_data_products, 'dia_iva' => $dia_iva, 'num_dsm'=>$dsi_data_dsms->num_dsm])
                    </div>
                <?php }
            }
            //dd($dia_iva);
        }
                        ?></div>
                        <div class="col-md-12" style="display: block;">
                            @if($editar_datos)
                            @if(isset($dia_iva) && isset($dsi_data_dsms) && $dsi_data_dsms->num_dsm!="")
                            <div class="col-md-3" style="display: block;">
                                <button type="button" onclick="addProduct(this);" class="agregar_doc_separado btn btn-primary">Agregar Otro Producto</button>
                            </div>
                            @else
                            <p>Es necesario guardar el documento de separado para agregar productos</p>
                            @endif
                            @endif
                        </div>
                    </div>
                </fieldset>
            </div>    
        </div>
    </div>
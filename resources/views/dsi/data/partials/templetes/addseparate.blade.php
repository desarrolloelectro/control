        <div class="col-md-12" style="display: block; text-align: right;">
        @if($ayuda)<button data-toggle="modal" data-target="#helpSeparate" class="close" style="float:left" type="button" title="Ayuda">?</button>@endif
        @if(!isset($dia_iva))
        <button title="Quitar este documento separado" class="close" type="button" onclick="removeItemPanel(this.parentNode)">x</button>
        @else
        <button title="No se puede quitar este documento separado" class="close" type="button" disabled>x</button>
        @endif    
        </div>
        <input hidden {{ isset($dia_iva) ? ' readonly ' : '' }} type="number" class="dsi_ant_dsm_id form-control" name="dsi_ant_dsm_id[]"  value="{{ isset($dia_iva) && isset($dsi_data_dsms) ? $dsi_data_dsms->id : '' }}">
    <div class="col-md-11">
        <div class="form-group" class="divseparado">       
            <div class="row justify-content-center">
                <fieldset class="col-12 col-md-12">
                    <legend><h1>Separado</h1></legend>
                    <div class="row justify-content-center">
                        <div class="col-md-4" style="display: block;">
                            <div class="form-group">
                                <label>Documento Separado mercancía</label> <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                                <input required="" required-marked="marked" {{ isset($dia_iva) ? '' : '' }} readonly type="text" class="dsi_ant_dsm form-control" name="dsi_ant_dsm[]" value="{{ isset($dia_iva) && isset($dsi_data_dsms) ? $dsi_data_dsms->dsm : $documentdsm }}">           
                            </div>
                        </div>
                        <div class="col-md-4" style="display: block;">
                            <div class="form-group">
                                <label>Número de documento</label> <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                                <input {{ isset($dia_iva) ? '' : '' }} type="number" class="dsi_ant_num_dsm form-control" name="dsi_ant_num_dsm[]" value="{{ isset($dia_iva) && isset($dsi_data_dsms) ? $dsi_data_dsms->num_dsm : '' }}">            
                            </div>
                        </div>
                    </div>
                    <h2>Productos</h2>
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
                            <div class="col-md-3" style="display: block;">
                                <button type="button" onclick="addProduct(this);" class="agregar_doc_separado btn btn-primary">Agregar Otro Producto</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </fieldset>
            </div>    
        </div>
    </div>
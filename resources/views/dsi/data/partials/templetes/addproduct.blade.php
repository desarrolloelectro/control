    <div class="col-md-12" style="display: block; text-align: right;">
    @if($ayuda) <button data-toggle="modal" data-target="#helpProduct" class="close" style="float:left" type="button" title="Ayuda">?</button>@endif
    @if(!isset($dia_iva))
    <button title="Quitar este producto de la lista" class="close" type="button" onclick="removeItemPanel(this.parentNode)">x</button>
    @else
        <button title="No se puede quitar este producto de la lista" class="close" type="button" disabled>x</button>
    @endif 
    </div>
    <input hidden {{ isset($dia_iva) ? ' disabled ' : '' }} type="number" class="productItemid" name="productItemid[{{ isset($num_dsm) ? $num_dsm : '${dsi_ant_num_dsm_value}' }}][]"  value="{{ isset($dia_iva) && isset($dsi_data_products) ? $dsi_data_products->id : '' }}">
    <div class="col-md-4" style="display: block;">
        <div class="form-group">
            <label>Producto</label>
            <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
            <div class="input-group">
                <input required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : ' readonly=readonly style=background-color:#fff!important;' }} type="text" class="nombre form-control" name="productItemnombre[{{ isset($num_dsm) ? $num_dsm : '${dsi_ant_num_dsm_value}' }}][]" value="{{ isset($dia_iva) && isset($dsi_data_products) ? $dsi_data_products->nombre : '' }}">
                <div class="input-group-append">
                    @if(isset($dia_iva))
                    <span disabled class="input-group-text fa fa-search" id="basic-addon2"></span>
                    @else
                    <span data-toggle="modal" data-target="#modalbuscarProductos" onclick="sendRef(this)" class="input-group-text fa fa-search" style="background-color: #fff !important;" id="basic-addon2"></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2" style="display: block;">
        <div class="form-group">
            <label>Referencia</label>
            <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
            <input required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : ' readonly=readonly style=background-color:#fff!important;' }} type="text" class="referencia form-control" name="productItemreferencia[{{ isset($num_dsm) ? $num_dsm : '${dsi_ant_num_dsm_value}' }}][]" value="{{ isset($dia_iva) && isset($dsi_data_products) ? $dsi_data_products->referencia : '' }}">           
        </div>
    </div>
    <div class="col-md-3" style="display: block;">
        <div class="form-group">
            <label>Serial</label>
            <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
            <input required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : '' }} type="text" class="serial form-control" name="productItemserial[{{ isset($num_dsm) ? $num_dsm : '${dsi_ant_num_dsm_value}' }}][]" value="{{ isset($dia_iva) && isset($dsi_data_products) ? $dsi_data_products->serial : '' }}">            
        </div>
    </div>
    <div class="col-md-3" style="display: block;">
        <div class="form-group">
            <label>Valor</label>
            <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
            <div class="input-group">
                <div class="input-group-append">
                    <span title="" class="title_valor input-group-text">$</span>
                </div>
                <input required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : '' }} type="number" class="valor numeroALetras form-control" name="productItemvalor[{{ isset($num_dsm) ? $num_dsm : '${dsi_ant_num_dsm_value}' }}][]" value="{{ isset($dia_iva) && isset($dsi_data_products) ? $dsi_data_products->valor : '' }}">
                <input type="hidden" class="linea" name="productItemlinea[{{ isset($num_dsm) ? $num_dsm : '${dsi_ant_num_dsm_value}' }}][]" value="{{ isset($dia_iva) && isset($dsi_data_products) ? $dsi_data_products->linea : '' }}">
            </div>            
        </div>
    </div>
    <div class="col-md-12" style="display: block;">
    @if(isset($dsi_data_products) && $dsi_data_products->id)
    <h3>Anticipos relacionados a este producto  @if($editar_datos)
    <button type="button" class="btn btn-warning" onclick="document.getElementById('id_producto_para_anticipo').value='{{ $dsi_data_products->id }}';document.getElementById('ref_producto_para_anticipo').value='{{ $dsi_data_products->referencia }}'; " data-toggle="modal" data-target="#searchAdvancesProduct">Agregar</button>
    @endif
    </h3>
    @if (!empty($dsi_data_products->dsi_data_all_advances) && count($dsi_data_products->dsi_data_all_advances)>0)
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Ref</th>
                <th>Fecha</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody id="products_with_advances">
            
                    @php $total = 0; @endphp
                
                    @foreach($dsi_data_products->dsi_data_all_advances as $prod_advances )
                        @php
                        $total += $prod_advances->pivot->value;
                        @endphp
                        <tr>
                            <td>{{ $prod_advances->num_recibo }}</td>
                            <td>{{ custom_date_format($prod_advances->fecha_recibo, "d/m/Y") }}</td>
                            <td>{{ custom_currency_format($prod_advances->pivot->value) }}</td>
                        </tr>
                    @endforeach
        </tbody>
        <tfoot id="total_products_with_advances">
            @if($total>0)
            <tr>
                <td></td>
                <td>Total</td>
                <td><strong>{{ custom_currency_format($total) }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Valor Producto</td>
                <td>{{ custom_currency_format($dsi_data_products->valor) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Saldo </td>
                <td><strong><?php $saldo = $dsi_data_products->valor - $total ?>{{ custom_currency_format($saldo) }}</strong></td>
            </tr>
            @endif
        </tfoot>
    </table>
    @else
    <p>Aún no tiene relacionados anticipos a este producto</p>
    @endif
    @else
    <p>Aún no tiene relacionados anticipos a este producto, es necesario guardar para asignar anticipos a este producto</p>
    @endif
    </div>

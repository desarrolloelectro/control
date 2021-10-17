<!-- Modal -->
<div class="modal fade" id="searchAdvancesProduct" tabindex="-1" role="dialog" aria-labelledby="helpProductTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Anticipos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <input type="hidden" name="dsi_data_product_id" value="" id="id_producto_para_anticipo">
      <input type="hidden" name="ref_producto_para_anticipo" value="" id="ref_producto_para_anticipo">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Fecha</th>
                    <th>Valor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="empty_advances"><?php $total=0;
        if(isset($dia_iva)){
            if(!empty($dia_iva->dsi_data_advances)){
              foreach($dia_iva->dsi_data_advances as $id => $dsi_data_advances){ 
                if($dsi_data_advances->dsi_data_product_id == ''){
                $total+=$dsi_data_advances->valor_recibo;
                ?>
                    <tr>
                        <td>{{ $dsi_data_advances->num_recibo }}</td>
                        <td>{{ custom_date_format($dsi_data_advances->fecha_recibo, "d/m/Y") }}</td>
                        <td>{{ custom_currency_format($dsi_data_advances->valor_recibo) }}</td>
                        <td>
                          <button type="button" onclick="asociarProductoAnticipo(document.getElementById('id_producto_para_anticipo').value,{{ $dsi_data_advances->id }},'Confirma que desea utilizar el anticipo de {{ custom_currency_format($dsi_data_advances->valor_recibo) }} en el producto con referencia '+document.getElementById('ref_producto_para_anticipo').value+'?');" class="btn btn-success">Seleccionar</button>
                        </td>
                    </tr>
                <?php } }
            }
        }
        ?></tbody>
            <tfoot id="total_advances">
                <tr>
                    <td></td>
                    <td>Total</td>
                    <td>{{ custom_currency_format($total) }}</td>
                </tr>
            </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>
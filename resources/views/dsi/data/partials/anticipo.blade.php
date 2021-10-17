<?php
//dd($dia_iva);
?>
<div class="row">
    <div class="col-md-3" style="display:block;">
    <div class="form-group">
            <label>Tipo venta</label>
            <select class="form-control" name="tipoventa" id="tipoventa" {{ (isset($dia_iva) && $dia_iva->id != null) ? ' disabled ' : '' }} required>
                <option value="">Seleccione Tipo venta</option>
                @foreach($tiposventa as $tiposventai)
                <option value="{{ $tiposventai }}" {{ (isset($dia_iva) && isset($dia_iva->tipoventa) && $dia_iva->tipoventa == $tiposventai) || count($tiposventa)==1 ? " selected ": $dia_iva->tipoventa }}>{{ $tiposventai }}</option>
                @endforeach 
            </select>               
        </div>
    </div>
</div>
<center style="width: 100%;display:{{ (isset($dia_iva) && isset($dia_iva->tipoventa) && $dia_iva->tipoventa == 'Anticipo') ? 'block': 'none'}};">
    <div class="col-md-11">
        <div class="form-group" id="divanticipo">       
            <div class="row justify-content-center">
                <fieldset class="col-12 col-md-12 px-3">
                    <legend><h1>Anticipos:</h1></legend>
                    <div id="advances"><?php 
                    if(isset($dia_iva)){
                        if(!empty($dia_iva->dsi_data_advances)){
                            foreach($dia_iva->dsi_data_advances as $id => $dsi_data_advances){ ?>
                                @include('dsi.data.partials.templetes.addadvance',['documentsm' => $documentsm, 'ayuda' => $ayuda, 'dsi_data_advances' => $dsi_data_advances])
                            <?php }
                        }
                        //dd($dia_iva);
                    }
                    ?></div>
                    <div>
                        <button type="button" onclick="addAdvance();" title="Agregar documento separado" id="agregar_doc_separado" 
                        style="" class="btn btn-primary">
                        Agregar Otro Anticipo
                        </button>
                        <br>
                        <br>
                    </div>
                </fieldset>
            </div>    
        </div>
    </div>
</center>
<div style="display:{{ (isset($dia_iva) && $dia_iva != null && isset($dia_iva->tipoventa) && $dia_iva->tipoventa == 'Anticipo') ? 'block': 'none'}};">
    <div id="separados"><?php 
        if(isset($dia_iva) && $dia_iva != null){
            if(!empty($dia_iva->dsi_data_dsms)){
                //dsi_data_products
                foreach($dia_iva->dsi_data_dsms as $id => $dsi_data_dsms){ ?>
                <center class="separateItem">
                    @include('dsi.data.partials.templetes.addseparate',['documentdsm' => $documentdsm, 'ayuda' => $ayuda, 'dsi_data_dsms' => $dsi_data_dsms])
                </center>                         
                <?php }
            }
            //dd($dia_iva);
        }
    ?></div>
    <div>
        <button type="button" onclick="addSeparate();" title="Agregar documento separado" id="agregar_doc_separado" 
        style="" class="btn btn-primary">
        Agregar Otro Separado
        </button>
        <input type="submit" id="btn-enviar2" name="submit" value="{{ $boton2 }}"  class="btn btn-success">
        <br>
        <br>
    </div>
</div>
@include('dsi.data.partials.styles')
@include('dsi.data.partials.scripts',['$documentsm' => $documentsm, 'documentdsm' => $documentdsm, 'ayuda' => $ayuda])
@if($ayuda)
<!-- Modal -->
<div class="modal fade" id="helpAdvance" tabindex="-1" role="dialog" aria-labelledby="helpAdvanceTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Ayuda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            Tipo recibo:
            Número recibo:
            Valor recibo:
            Fecha recibo:
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="helpSeparate" tabindex="-1" role="dialog" aria-labelledby="helpSeparateTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Ayuda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          Separado
            Tipo recibo:
            Número recibo:
            Valor recibo:
            Fecha recibo:
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="helpProduct" tabindex="-1" role="dialog" aria-labelledby="helpProductTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Ayuda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        helpProduct
            Tipo recibo:
            Número recibo:
            Valor recibo:
            Fecha recibo:
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>
@endif
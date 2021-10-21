<div class="advanceItem" style="border:solid 1px #cccccc;border-radius:10px;padding:5px;margin: 5px 0px;">
    <div class="col-md-12" style="display: block; text-align: right;">
        @if($ayuda)<button data-toggle="modal" data-target="#helpAdvance" class="close" style="float:left" type="button" title="Ayuda">?</button>@endif
        @if(!isset($dia_iva))
        <button title="Quitar este anticipo de la lista" class="close" type="button" onclick="removeItemPanel(this.parentNode)">x</button>
        @else
        <button title="No se puede quitar este anticipo de la lista" class="close" type="button" disabled>x</button>
        @endif
    </div>
    <input hidden {{ isset($dia_iva) ? ' disabled ' : '' }} type="number" class="ant_id form-control" name="ant_id[]"  value="{{ isset($dia_iva) && isset($dsi_data_advances) ? $dsi_data_advances->id : '' }}">
    <div class="row justify-content-center">
        <div class="col-md-3" style="display: block;">
            <div class="form-group">
                <label>Tipo recibo</label>
                <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                <select required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : '' }}  type="text" class="form-control" name="ant_tipo_recibo[]" >
                    <option value="">Selecciones un tipo de recibo</option>
                    @foreach($documentsm as $dsmkey => $dsmvalue)
                    <option value="{{ $dsmkey }}" {{ (isset($dia_iva) && isset($dsi_data_advances) && $dsi_data_advances->tipo_recibo==$dsmkey) ? " selected " : "" }}>{{ $dsmvalue }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3" style="display: block;">
            <div class="form-group">
                <label>Número recibo</label>
                <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                <input required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : '' }} type="number" class="ant_num_recibo form-control" name="ant_num_recibo[]"  value="{{ isset($dia_iva) && isset($dsi_data_advances) ? $dsi_data_advances->num_recibo : '' }}">
            </div>
        </div>
        <div class="col-md-3" style="display: block;">
            <div class="form-group">
                <label>Valor recibo</label>
                <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                <div class="input-group">
                    <div class="input-group-append">
                        <span title="" class="title_valor input-group-text">$</span>
                    </div>
                    <input required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : '' }} type="number" class="numeroALetras form-control" name="ant_vr_recibo[]"  value="{{ isset($dia_iva) && isset($dsi_data_advances) ? $dsi_data_advances->valor_recibo : '' }}">
                </div>
            </div>
        </div>
        <div class="col-md-3" style="display: block;">
            <div class="form-group">
                <label>Fecha recibo</label>
                <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                <input required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : '' }} type="date" class="form-control" name="ant_fecha_recibo[]"  value="{{ isset($dia_iva) && isset($dsi_data_advances) ? $dsi_data_advances->fecha_recibo : '' }}">
            </div>
        </div>
        <div class="col-md-3" style="display: block;">
            <div class="form-group">
                <label>Identificación Cliente</label>
                <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                <input required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : '' }} type="number" class="form-control" name="ant_cliente_id[]"  value="{{ isset($dia_iva) && isset($dsi_data_advances) ? $dsi_data_advances->cliente_id : '' }}">
            </div>
        </div>
        <div class="col-md-3" style="display: block;">
            <div class="form-group">
                <label>Nombre Cliente</label>
                <i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>
                <input required="" required-marked="marked" {{ isset($dia_iva) ? ' disabled ' : '' }} type="text" class="form-control" name="ant_cliente_nombre[]" value="{{ isset($dia_iva) && isset($dsi_data_advances) ? $dsi_data_advances->cliente_nombre : '' }}">
            </div>
        </div>
        <div class="col-md-3" style="display: block;">
            <div class="form-group">
                <label>Estado</label>
                @isset($dia_iva)
                    <div>
                    @if(isset($dsi_data_advances))
                        @if($dsi_data_advances->saldo == 0)
                            <button type="button" class="btn2 btn-success" title="Este anticipo ya ha sido utilizado"><i class="fa fa-check"></i></button> Utilizado
                        @elseif($dsi_data_advances->saldo == $dsi_data_advances->valor_recibo)
                            <button type="button" class="btn2 btn-warning" title="Este anticipo aún no ha sido utilizado"><i class="fa fa-warning"></i></button> Pendiente
                        @else
                            <button type="button" class="btn2 btn-warning" title="Este anticipo aún tiene saldo disponible"><i class="fa fa-money"></i></button> Saldo {{ custom_currency_format($dsi_data_advances->saldo) }}
                        @endif
                    @endif
                    </div>
                @endisset
            </div>
        </div>
        
    </div>
</div>
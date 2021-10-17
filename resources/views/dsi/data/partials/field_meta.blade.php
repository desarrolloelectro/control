@php
$dia_iva->dsi_meta_value($dsi_meta->id);
@endphp
<div class = "col-md-3" style="display:{{ $child==true ? 'none' : 'block' }}">
    <div class="form-group">
        <label>{{ $dsi_meta->field_name }}</label>
            @if($dsi_meta->type=='text')
            <input required  
                type="text" 
                class="form-control" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}" value="{{ isset($dia_iva->dsi_meta_value->value) ? $dia_iva->dsi_meta_value->value : "" }}">
            @elseif($dsi_meta->type=='number')
            <input required  
                type="number"
                class="form-control" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}" value="{{ isset($dia_iva->dsi_meta_value->value) ? $dia_iva->dsi_meta_value->value : "" }}">
            @elseif($dsi_meta->type=='currency')
            <input required  
                type="number"
                class="form-control" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}" value="{{ isset($dia_iva->dsi_meta_value->value) ? $dia_iva->dsi_meta_value->value : "" }}">
            @elseif($dsi_meta->type=='date')
            <input required  
                type="date"
                class="form-control" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}" value="{{ isset($dia_iva->dsi_meta_value->value) ? $dia_iva->dsi_meta_value->value : "" }}">
            @elseif($dsi_meta->type=='time')
            <input required  
                type="time"
                class="form-control" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}" value="{{ isset($dia_iva->dsi_meta_value->value) ? $dia_iva->dsi_meta_value->value : "" }}">
            @elseif($dsi_meta->type=='datetime')
            <input required  
                type="datetime"
                class="form-control" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}" value="{{ isset($dia_iva->dsi_meta_value->value) ? $dia_iva->dsi_meta_value->value : "" }}">
            @elseif($dsi_meta->type=='file')
            <input required  
                type="file"
                class="form-control" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}" value="{{ isset($dia_iva->dsi_meta_value->value) ? $dia_iva->dsi_meta_value->value : "" }}">
            @elseif($dsi_meta->type=='list')
            @php $options = json_decode($dsi_meta->options) @endphp
            <select class="form-control" name="type" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}">
                <option value="">Seleccione {{ $dsi_meta->field_name }}</option>
                @foreach($options as $key => $option)
                    <option value="{{ $option }}" {{ isset($dia_iva->dsi_meta_value->value) ? ($dia_iva->dsi_meta_value->value == $option) ? " selected " : "" : "" }} >{{ $option }}</option>
                @endforeach
            </select>
            @elseif($dsi_meta->type=='textarea')
            <textarea required class="form-control" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}">
            {{ isset($dia_iva->dsi_meta_value->value) ? $dia_iva->dsi_meta_value->value : "" }}
            </textarea>
            @else
            <input required  
                type="text"
                class="form-control" name="dsi_meta[{{$dsi_meta->id}}]" id="dsimeta{{$dsi_meta->id}}" value="">
            @endif
            
    </div>
</div>
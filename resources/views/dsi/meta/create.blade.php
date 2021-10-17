<script>
    function select_list(value){
        var options = document.getElementById('options');
        var options_items = document.getElementById('options_items');
        options.style.display = (value=='list') ? 'block' : 'none';
        options_items.innerHTML = '';
    }
    function add_field_option(){
    var templete = '<input class="form-control options_item" name="options[]" type="text"><input class="input-group-append btn-danger" onClick="delete_field_option(this);" type="button" value="-">';
    var options = document.getElementById('options_items');
    var aux = document.createElement("div");
    aux.classList.add('options');
    aux.classList.add('input-group');
    aux.innerHTML = templete;
    options.appendChild(aux);
    aux.firstChild.focus();
    }
    
    function delete_field_option(obj){
        obj.parentNode.parentNode.removeChild(obj.parentNode);
    }

    window.addEventListener('DOMContentLoaded', (event) => {
        var el = document.querySelector('.toggler');
        el.onclick = function() {
            att = el.getAttribute('toggle-target');
            var eld = document.querySelector('#'+att);
            eld.classList.toggle('div_hidden');
        }
    });
</script>
<style>
    .div_hidden {
        /*transition-delay: 2s;*/
        visibility:hidden;
        opacity:0;
        transition:visibility 0.3s linear,opacity 0.3s linear;
        display:none;
    }
    .options {
        margin: 7px 0px;
    }
</style>
<div id="form_create" class="div_hidden">
    <ul>
    @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
    @endforeach
    </ul>
    <form method="POST" action="{{ route('dsi.meta.store', ['dsi_id' => $dsi->id]) }}">
        @csrf
        <div class="form-group">
            <label for="field_name">Nuevo Campo</label>
            <input name="dsi_id" type="hidden" value="{{ $dsi->id }}">
            <input id="field_name" class="form-control" name="field_name" type="text">
        </div>
        <div class="form-group">
            <label for="attribs_required" class="form-control">
                <input id="attribs_required"  name="attribs[required]" value="required" type="checkbox"> Requerido
            </label>
            
        </div>
        <div class="form-group">
            <label for="type">Tipo</label>
            <select id="type" class="form-control" name="type" onchange="select_list(this.value)">
                @foreach($types as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" id="options" style="display:none">
            <div><label for="options">Opciones <input class="btn-success" onClick="add_field_option();" type="button" value="+"></label></div>
            <span id="options_items">
            <span>
        </div>

        <div class="form-group">
            <label for="parent">Este campo depende de otro campo?</label>
            <select id="parent" class="form-control" name="parent" onchange="select_list(this.value)">
            <option value="">No</option>
            @foreach($dsi_meta_parent as $keyp => $valuep)
                <option value="{{ $keyp }}">{{ $valuep }}</option>
            @endforeach
            </select>
        </div>
        <div id="div-parent_value" class="form-group" style = 'display:none;'>
            <label for="parent_value">Valor campo del cual depende</label>
            <input id="parent_value" class="form-control" name="parent_value" type="text">
        </div>

        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="Guardar">
        </div>
    </form>
</div>
<script>
    var parent = document.getElementById("parent");
    if(parent){
        parent.addEventListener('change', function(){
            divpv = document.getElementById("div-parent_value");
            cpv = document.getElementById("parent_value");
            if(divpv){
                if(this.value!=""){
                    divpv.style.display = 'block';
                }else{
                    divpv.style.display = 'none';
                    cpv.value='';
                }
            }
        });
    }
</script>
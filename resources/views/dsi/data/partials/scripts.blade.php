<script type="text/javascript">
    window.CSRF_TOKEN = '{{ csrf_token() }}';
</script>
<script src="{{ asset('docs/js/numero_a_letras.js') }}"></script>
<script>
    function load_event_dsi_ant_num_dsm(){
        var dsi_ant_num_dsm = document.querySelectorAll('.dsi_ant_num_dsm');
        dsi_ant_num_dsm.forEach(function(dsi_ant_num_dsmItem) {
            dsi_ant_num_dsmItem.addEventListener('change', function(){
                setNames(this);
            });
        });
    }
    function numeroALetrasAll(){
        var mtt = document.querySelectorAll('.numeroALetras');
        mtt.forEach(function(mttItem) {
            mttItem.addEventListener('change', function(){
                convertirnumeroALetrasAll(this);
            });
        });
    }
    function convertirnumeroALetrasAll(obj){
        obj.parentNode.querySelector('.title_valor').setAttribute("title",numeroALetras(obj.value));
    }

    function setNames(obj){
        var sep = document.getElementById('separados');
        var objs = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
        var refs_val = [...objs.parentNode.children].indexOf(objs);
        var productsItems = sep.children[refs_val].querySelector(".products").childNodes;
        
        productsItems.forEach(function(productItem) {
            productItem.querySelector(".productItemid").setAttribute("name",`productItemid[${obj.value}][]`);
            productItem.querySelector(".nombre").setAttribute("name",`productItemnombre[${obj.value}][]`);
            productItem.querySelector(".referencia").setAttribute("name",`productItemreferencia[${obj.value}][]`);
            productItem.querySelector(".valor").setAttribute("name",`productItemvalor[${obj.value}][]`);
            productItem.querySelector(".serial").setAttribute("name",`productItemserial[${obj.value}][]`);
            productItem.querySelector(".linea").setAttribute("name",`productItemlinea[${obj.value}][]`);
        });
    }
function attrfield(id,value,att){
    obj = document.getElementById(id);
    if(value) obj.setAttribute(att,att); 
    else obj.removeAttribute(att);
}

function requeridos(value){
    attrfield('tipofac',value,"required");
    attrfield('tipodoc',value,"required");
    attrfield('numdoc',value,"required");
    attrfield('categoria',value,"required");
    attrfield('genero',value,"required");
    attrfield('unidad',value,"required");
    attrfield('cantidad',value,"required");
    attrfield('vrunit',value,"required");
    attrfield('vrtotal',value,"required");
    attrfield('descripcion',value,"required");
    attrfield('pvppublico',value,"required");
    attrfield('mediopago',value,"required");
    attrfield('numsoporte',value,"required");
    attrfield('urlimagen',value,"required");
    attrfield('fecha',value,"required");
    attrfield('lugar',value,"required");

    attrfield('tipofac',!value,"disabled");
    attrfield('tipodoc',!value,"disabled");
    attrfield('numdoc',!value,"disabled");
    attrfield('categoria',!value,"disabled");
    attrfield('genero',!value,"disabled");
    attrfield('unidad',!value,"disabled");
    attrfield('cantidad',!value,"disabled");
    attrfield('vrunit',!value,"disabled");
    attrfield('vrtotal',!value,"disabled");
    attrfield('descripcion',!value,"disabled");
    attrfield('pvppublico',!value,"disabled");
    attrfield('mediopago',!value,"disabled");
    attrfield('numsoporte',!value,"disabled");
    attrfield('urlimagen',!value,"disabled");
    attrfield('fecha',!value,"disabled");
    attrfield('lugar',!value,"disabled");
}
function change_medio_pago(mediopago){
    var numsoporte = document.getElementById("numsoporte");
        if(numsoporte){
            if(mediopago!="" && mediopago == '5'){
                numsoporte.parentNode.parentNode.style.display = 'none';
                attrfield('numsoporte',false,"required");
            }else{
                numsoporte.parentNode.parentNode.style.display = 'block';
                attrfield('numsoporte',true,"required");
            }
        }
    var urlimagen = document.getElementById("urlimagen");
    if(urlimagen){
        if(mediopago!="" && mediopago == '5'){
            urlimagen.parentNode.parentNode.style.display = 'none';
            attrfield('urlimagen',false,"required");
        }else{
            urlimagen.parentNode.parentNode.style.display = 'block';
            attrfield('urlimagen',true,"required");
        }
    }
}

function load_saldo_auto(){
    var valores_anticipo_usar_en_producto = document.querySelectorAll('.valor_anticipo_usar_en_producto');
    valores_anticipo_usar_en_producto.forEach(function(valor_anticipo_usar_en_producto) {
      if(valor_anticipo_usar_en_producto){
        valor_anticipo_usar_en_producto.addEventListener('blur',function(e){
          var valor_producto = document.getElementById('valor_producto_para_anticipo').value;
          var max = parseInt(this.getAttribute('max'));
          var value = parseInt(this.value);
          var saldo_anticipo_usar_en_producto = valor_anticipo_usar_en_producto.parentNode.parentNode.querySelector('.saldo_anticipo_usar_en_producto');
          //console.log(valor_anticipo_usar_en_producto.parentNode.parentNode);
            var validasaldo = parseInt(valor_producto) - parseInt(value);
            if (validasaldo<0){
                alert('Por favor revise los datos ingresados, El valor ingresado es superior al valor del producto');
            }
            if(max >= value){
              var nuevosaldo = parseInt(max) - parseInt(value);
              saldo_anticipo_usar_en_producto.setAttribute("saldo", nuevosaldo);
              var nuevosaldo = `$ ${ custom_currency_format(nuevosaldo) }`;
              saldo_anticipo_usar_en_producto.innerHTML=nuevosaldo;
              //this.parentNode.parentNode.querySelector('.saldo_anticipo_usar_en_producto').getAttribute("saldo")
            }else{
              alert('Por favor revise los datos ingresados, El valor ingresado es superior al valor del saldo');
              this.value = '0';
              saldo_anticipo_usar_en_producto.setAttribute("saldo", max);
              saldo_anticipo_usar_en_producto.innerHTML=`$ ${ custom_currency_format(max) }`;
            }
        });
      }
    });
  }
 
    window.addEventListener('DOMContentLoaded', (event) => {
            load_saldo_auto();
            numeroALetrasAll();
            load_event_dsi_ant_num_dsm();
            var mediopago = document.getElementById("mediopago");
            if(mediopago){
                mediopago.addEventListener('change', function(){
                    change_medio_pago(this.value);
                });
                change_medio_pago(mediopago.value);
            }
            var vartipoventa = document.getElementById("tipoventa");
            if(vartipoventa){
                vartipoventa.addEventListener('change', function(){
                    tipoventachange(this);
                });
                tipoventachange(vartipoventa);
            }
        document.querySelectorAll('.required_en_formulario').forEach(function(form) {
            required_en_formulario(form.id,color="red",elemento="*");
        });
        
    });//DOM Loaded Content
    function tipoventachange(tipoventa){
        var separados = document.getElementById("separados");
                    var advances = document.getElementById("advances");
                    if(tipoventa.value!="" && tipoventa.value == 'Anticipo'){   
                        //requeridos(false) ;
                        if(separados.children.length==0){
                            addSeparate();
                        }
                        if(advances.children.length==0){
                            addAdvance();
                        }                    
                    }else{
                        //requeridos(true);
                        if(separados.children.length>0 || advances.children.length>0){
                            if(confirm("Esta seguro que abandonar el Anticipo?, tenga en cuenta que perderá los datos ingresados en la sección anticipo")){
                                removeAllChildNodes(separados);
                                removeAllChildNodes(advances);
                            }else{
                                tipoventa.value = 'Anticipo';
                            }
                        }    
                    }
                cpvm5 = document.getElementById("ant_tipo_recibo");
                if(cpvm5){
                    if(tipoventa.value!="" && tipoventa.value == 'Anticipo'){
                        //console.log(cpvm5.parentNode.parentNode);
                        cpvm5.parentNode.parentNode.style.display = 'block';
                    }else{
                        cpvm5.parentNode.parentNode.style.display = 'none';
                        //cpvm5.value='';
                    }
                }
                cpvm6 = document.getElementById("ant_num_recibo");
                if(cpvm6){
                    if(tipoventa.value!="" && tipoventa.value == 'Anticipo'){
                        //console.log(cpvm6.parentNode.parentNode);
                        cpvm6.parentNode.parentNode.style.display = 'block';
                    }else{
                        cpvm6.parentNode.parentNode.style.display = 'none';
                        //cpvm6.value='';
                    }
                }
                cpvm7 = document.getElementById("ant_vr_recibo");
                if(cpvm7){
                    if(tipoventa.value!="" && tipoventa.value == 'Anticipo'){
                        //console.log(cpvm7.parentNode.parentNode);
                            cpvm7.parentNode.parentNode.style.display = 'block';
                    }else{
                            cpvm7.parentNode.parentNode.style.display = 'none';
                            //cpvm7.value='';
                    }
                }
                cpvm8 = document.getElementById("dsimeta8");
                if(cpvm8){
                    if(tipoventa.value!="" && tipoventa.value == 'Anticipo'){
                        //console.log(cpvm8.parentNode.parentNode);
                        cpvm8.parentNode.parentNode.style.display = 'block';
                    }else{
                        cpvm8.parentNode.parentNode.style.display = 'none';
                        //cpvm8.value='';
                    }
                }
                cpvm9 = document.getElementById("dsimeta9");
                if(cpvm9){
                    if(tipoventa.value!="" && tipoventa.value == 'Anticipo'){
                        //console.log(cpvm9.parentNode.parentNode);
                        cpvm9.parentNode.parentNode.style.display = 'block';
                    }else{
                        cpvm9.parentNode.parentNode.style.display = 'none';
                        //cpvm9.value='';
                    }
                }
                divanticipo = document.getElementById("divanticipo");
                if(divanticipo){
                    if(tipoventa.value!="" && tipoventa.value == 'Anticipo'){
                        //console.log(divanticipo.parentNode.parentNode);
                        divanticipo.parentNode.parentNode.style.display = 'block';
                    }else{
                        divanticipo.parentNode.parentNode.style.display = 'none';
                        //divanticipo.value='';
                    }
                }
                divseparado = document.getElementById("divseparado");
                if(divseparado){
                    if(tipoventa.value!="" && tipoventa.value == 'Anticipo'){
                        //console.log(divseparado.parentNode.parentNode);
                        divseparado.parentNode.parentNode.style.display = 'block';
                    }else{
                        divseparado.parentNode.parentNode.style.display = 'none';
                        //divseparado.value='';
                    }
                }
                separados = document.getElementById("separados");
                if(separados){
                    if(tipoventa.value!="" && tipoventa.value == 'Anticipo'){
                        //console.log(separados.parentNode.parentNode);
                        separados.parentNode.style.display = 'block';
                    }else{
                        separados.parentNode.style.display = 'none';
                        //separados.value='';
                    }
                }
    }
    function required_en_formulario(id_formulario,color,elemento){
            var formulario = document.getElementById(id_formulario);
            if (formulario){
                var inputs = ['select','input'];                
                inputs.forEach(function(inputname) {
                   formulario.querySelectorAll(inputname).forEach(function(input) {
                        var atr = input.getAttribute('type');
                        if(atr || inputname == 'select'){
                            if((atr!="hidden" && atr!="button")  || inputname == 'select'){
                                inputrequired = input.getAttribute("required");
                                requiredmarked = input.getAttribute("required-marked");
                                if(requiredmarked==null && inputrequired!=null){
                                    input.setAttribute("required-marked","marked");
                                    var font = document.createElement('font');
                                    templete = '<i class="fa fa-asterisk" style="color:red;font-size:8px;"></i>';
                                    font.innerHTML = templete;
                                    if (input.parentNode.classList.contains("form-group")){
                                        input.parentNode.insertBefore(font.firstChild,input);
                                    }else if (input.parentNode.parentNode.classList.contains("form-group")){
                                        input.parentNode.parentNode.insertBefore(font.firstChild,input.parentNode);
                                    }  
                                }  
                            }
                        }
                    });
                });
            }
        }
    function removeAllChildNodes(parent) {
        while (parent.firstChild) {
            parent.removeChild(parent.firstChild);
        }
    }
    function addAdvance(obj){
    var templete = `@include('dsi.data.partials.templetes.addadvance',['documentsm' => $documentsm, 'ayuda' => $ayuda, 'dia_iva' => null])`;
        var anticipos = document.getElementById("advances");
        //console.log(anticipos);
        var aux = document.createElement("div");
            aux.innerHTML = templete;    
            anticipos.appendChild(aux.firstChild);
            anticipos.querySelector(".ant_num_recibo").focus();
            numeroALetrasAll();
    }
    function addSeparate(){
        var templete = `@include('dsi.data.partials.templetes.addseparate',['documentdsm' => $documentdsm, 'ayuda' => $ayuda, 'dia_iva' => null])`;
        var separados = document.getElementById("separados");
        var aux = document.createElement("center");
        aux.classList.add("separateItem");
        aux.innerHTML = templete;    
        separados.appendChild(aux);
        aux.querySelector(".dsi_ant_num_dsm:last-child").focus();
        load_event_dsi_ant_num_dsm();

    }
    function addProduct(obj){
        var sep = document.getElementById('separados');
        var objs = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
        var refs_val = [...objs.parentNode.children].indexOf(objs);
        var dsi_ant_num_dsm_value = sep.children[refs_val].querySelector(".dsi_ant_num_dsm").value;
        //dsi_ant_num_dsm_value
        var templete = `@include('dsi.data.partials.templetes.addproduct',['ayuda' => $ayuda, 'dia_iva' => null])`;
        var productos = obj.parentNode.parentNode.parentNode.querySelector(".products");
        var aux = document.createElement("div");
        aux.classList.add("row");
        aux.classList.add("productItem");
        //aux.addAttrubure("ondblclick","list_products_with_advances(this)");
        aux.innerHTML = templete;    
        productos.appendChild(aux);
        aux.querySelector(".input-group-text.fa.fa-search").click();
        numeroALetrasAll();
    }
    function removeItemPanel(nodo){
        nodo.parentNode.parentNode.removeChild(nodo.parentNode);
    }
       /*
archiveadvance
archiveproduct
    */
    function removeItemAdvance(dsi_id,id,nodo){
        var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");

        var myInit = { method: 'GET',
            headers: myHeaders,
            mode: 'same-origin',
            //body: form,
            cache: 'default' };
        var _token ='{{ csrf_token() }}';
        var params = '?dsi_advance_id='+id+'&dsi_id='+dsi_id+'&_token='+_token;
        var url = '{{ route('dsi.archiveAdvance') }}'+params;
        
        if(confirm("Esta seguro?")){
            var myRequest = new Request(url, myInit);
            fetch(myRequest)
            .then(function(data) {
                if(loading) loading.style.display = 'none';
            var contentType = data.headers.get("content-type");
                if(contentType && contentType.indexOf("application/json") !== -1) {
                    return data.json().then(function(json) {
                    console.log(json);
                    if(json.success){
                        alert("Registro archivado");
                        nodo.parentNode.parentNode.removeChild(nodo.parentNode);
                    }
                    });
                }else{
                console.log("Error, no se consultaron datos!");
                }
            });
        
        }
    }
    function sendRef(obj){
        var objp = obj.parentNode.parentNode.parentNode.parentNode.parentNode;
        var objs = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
        var refp = document.getElementById('refp');
        var refs = document.getElementById('refs');
        refp.value=[...objp.parentNode.children].indexOf(objp);
        refs.value=[...objs.parentNode.children].indexOf(objs);
    }
    var MD5 = function(d){var r = M(V(Y(X(d),8*d.length)));return r.toLowerCase()};function M(d){for(var _,m="0123456789ABCDEF",f="",r=0;r<d.length;r++)_=d.charCodeAt(r),f+=m.charAt(_>>>4&15)+m.charAt(15&_);return f}function X(d){for(var _=Array(d.length>>2),m=0;m<_.length;m++)_[m]=0;for(m=0;m<8*d.length;m+=8)_[m>>5]|=(255&d.charCodeAt(m/8))<<m%32;return _}function V(d){for(var _="",m=0;m<32*d.length;m+=8)_+=String.fromCharCode(d[m>>5]>>>m%32&255);return _}function Y(d,_){d[_>>5]|=128<<_%32,d[14+(_+64>>>9<<4)]=_;for(var m=1732584193,f=-271733879,r=-1732584194,i=271733878,n=0;n<d.length;n+=16){var h=m,t=f,g=r,e=i;f=md5_ii(f=md5_ii(f=md5_ii(f=md5_ii(f=md5_hh(f=md5_hh(f=md5_hh(f=md5_hh(f=md5_gg(f=md5_gg(f=md5_gg(f=md5_gg(f=md5_ff(f=md5_ff(f=md5_ff(f=md5_ff(f,r=md5_ff(r,i=md5_ff(i,m=md5_ff(m,f,r,i,d[n+0],7,-680876936),f,r,d[n+1],12,-389564586),m,f,d[n+2],17,606105819),i,m,d[n+3],22,-1044525330),r=md5_ff(r,i=md5_ff(i,m=md5_ff(m,f,r,i,d[n+4],7,-176418897),f,r,d[n+5],12,1200080426),m,f,d[n+6],17,-1473231341),i,m,d[n+7],22,-45705983),r=md5_ff(r,i=md5_ff(i,m=md5_ff(m,f,r,i,d[n+8],7,1770035416),f,r,d[n+9],12,-1958414417),m,f,d[n+10],17,-42063),i,m,d[n+11],22,-1990404162),r=md5_ff(r,i=md5_ff(i,m=md5_ff(m,f,r,i,d[n+12],7,1804603682),f,r,d[n+13],12,-40341101),m,f,d[n+14],17,-1502002290),i,m,d[n+15],22,1236535329),r=md5_gg(r,i=md5_gg(i,m=md5_gg(m,f,r,i,d[n+1],5,-165796510),f,r,d[n+6],9,-1069501632),m,f,d[n+11],14,643717713),i,m,d[n+0],20,-373897302),r=md5_gg(r,i=md5_gg(i,m=md5_gg(m,f,r,i,d[n+5],5,-701558691),f,r,d[n+10],9,38016083),m,f,d[n+15],14,-660478335),i,m,d[n+4],20,-405537848),r=md5_gg(r,i=md5_gg(i,m=md5_gg(m,f,r,i,d[n+9],5,568446438),f,r,d[n+14],9,-1019803690),m,f,d[n+3],14,-187363961),i,m,d[n+8],20,1163531501),r=md5_gg(r,i=md5_gg(i,m=md5_gg(m,f,r,i,d[n+13],5,-1444681467),f,r,d[n+2],9,-51403784),m,f,d[n+7],14,1735328473),i,m,d[n+12],20,-1926607734),r=md5_hh(r,i=md5_hh(i,m=md5_hh(m,f,r,i,d[n+5],4,-378558),f,r,d[n+8],11,-2022574463),m,f,d[n+11],16,1839030562),i,m,d[n+14],23,-35309556),r=md5_hh(r,i=md5_hh(i,m=md5_hh(m,f,r,i,d[n+1],4,-1530992060),f,r,d[n+4],11,1272893353),m,f,d[n+7],16,-155497632),i,m,d[n+10],23,-1094730640),r=md5_hh(r,i=md5_hh(i,m=md5_hh(m,f,r,i,d[n+13],4,681279174),f,r,d[n+0],11,-358537222),m,f,d[n+3],16,-722521979),i,m,d[n+6],23,76029189),r=md5_hh(r,i=md5_hh(i,m=md5_hh(m,f,r,i,d[n+9],4,-640364487),f,r,d[n+12],11,-421815835),m,f,d[n+15],16,530742520),i,m,d[n+2],23,-995338651),r=md5_ii(r,i=md5_ii(i,m=md5_ii(m,f,r,i,d[n+0],6,-198630844),f,r,d[n+7],10,1126891415),m,f,d[n+14],15,-1416354905),i,m,d[n+5],21,-57434055),r=md5_ii(r,i=md5_ii(i,m=md5_ii(m,f,r,i,d[n+12],6,1700485571),f,r,d[n+3],10,-1894986606),m,f,d[n+10],15,-1051523),i,m,d[n+1],21,-2054922799),r=md5_ii(r,i=md5_ii(i,m=md5_ii(m,f,r,i,d[n+8],6,1873313359),f,r,d[n+15],10,-30611744),m,f,d[n+6],15,-1560198380),i,m,d[n+13],21,1309151649),r=md5_ii(r,i=md5_ii(i,m=md5_ii(m,f,r,i,d[n+4],6,-145523070),f,r,d[n+11],10,-1120210379),m,f,d[n+2],15,718787259),i,m,d[n+9],21,-343485551),m=safe_add(m,h),f=safe_add(f,t),r=safe_add(r,g),i=safe_add(i,e)}return Array(m,f,r,i)}function md5_cmn(d,_,m,f,r,i){return safe_add(bit_rol(safe_add(safe_add(_,d),safe_add(f,i)),r),m)}function md5_ff(d,_,m,f,r,i,n){return md5_cmn(_&m|~_&f,d,_,r,i,n)}function md5_gg(d,_,m,f,r,i,n){return md5_cmn(_&f|m&~f,d,_,r,i,n)}function md5_hh(d,_,m,f,r,i,n){return md5_cmn(_^m^f,d,_,r,i,n)}function md5_ii(d,_,m,f,r,i,n){return md5_cmn(m^(_|~f),d,_,r,i,n)}function safe_add(d,_){var m=(65535&d)+(65535&_);return(d>>16)+(_>>16)+(m>>16)<<16|65535&m}function bit_rol(d,_){return d<<_|d>>>32-_}
    
    function list_products_with_advances(myparent=''){
        if (myparent=="") myparent = document;
        var producto = myparent.querySelector(".productItemid").value;
        var id_producto_para_anticipo = 'idpro';
        var ref_producto_para_anticipo = 'refpro';

        var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");

        var myInit = { method: 'GET',
            headers: myHeaders,
            mode: 'same-origin',
            //body: form,
            cache: 'default' };

            var _token ='{{ csrf_token() }}';
            var params = '?p='+producto+'&_token='+_token;
            var url = '{{ route('dsi.listProductAdvance') }}'+params;
            
            var myRequest = new Request(url, myInit);
            var loading = myparent.querySelector('#loading');
            if(loading) loading.style.display = 'block';  
            fetch(myRequest)
            .then(function(data) {
            var loading = myparent.querySelector('#loading');
                if(loading) loading.style.display = 'none';
            var contentType = data.headers.get("content-type");
                if(contentType && contentType.indexOf("application/json") !== -1) {
                    return data.json().then(function(json) {
                    console.log(json);
                    var products_with_advances = myparent.querySelector('#products_with_advances');
                    products_with_advances.innerHTML = '';
                    var total = 0;
                    var valor = json.valor;
                    json.data.forEach(obj => {
                        total = total + obj.value;
                        //products_with_advances.innerHTML += `<tr data-dismiss="modal" title="Haga clic para seleccionar este producto" onclick="rellenarProducto('${btoa(obj.nombre)}', '${btoa(obj.referencia)}', '${btoa(obj.valor)}', '${btoa(obj.linea)}');">
                        products_with_advances.innerHTML += `<tr>
                        <td>${obj.num_recibo}</td>
                        <td>${obj.fecha_recibo}</td>
                        <td>$ ${ custom_currency_format(obj.value) }</td>
                        <td>
                        @if(Auth::user()->validar_permiso('dsi_developer'))
                            <button type="button" onclick="desasociarProductoAnticipo(${obj.pivot.id},${obj.dsi_data_product_id},'Confirma que desea dejar de utilizar el anticipo de $ ${ custom_currency_format(obj.valor_recibo) } en el producto con referencia ${ref_producto_para_anticipo}?')" class="btn btn-success">Retirar anticipo de producto</button>
                        @else
                            <button type="button" disabled class="btn btn-success">Retirar anticipo de producto</button>
                        @endif
                        </td>
                        <tr>`;
                    });           
                    var footerproducts_with_advances = myparent.querySelector('#total_products_with_advances');
                    footerproducts_with_advances.innerHTML = '';
                
                    if (total != 0){
                        var saldo = parseFloat(valor) - parseFloat(total);
                        footerproducts_with_advances.innerHTML = `<tr>
                            <td></td>
                            <td>Total</td>
                            <td><strong>$ ${ custom_currency_format(total) }</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Valor Producto</td>
                            <td>$ ${ custom_currency_format(valor) }</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saldo </td>
                            <td><strong>$ ${ custom_currency_format(saldo) }</strong></td>
                        </tr>
                        `;
                    }
                    
                    });
                }else{
                console.log("Error, no se consultaron datos!");
                }
            });
        
    }
    function desasociarProductoAnticipo2(producto,anticipo,mensaje=""){
        if(mensaje=="") mensaje="Confirma que desea dejar de utilizar el anticipo?";
        if (confirm(mensaje)){
            console.table({producto: producto,anticipo: anticipo,mensaje: mensaje});
        }
    }
    function custom_currency_format(value)
    {
        return new Intl.NumberFormat("de-DE").format(value);
    }
 
    function desasociarProductoAnticipo(value,id_producto,message='')
    {
        if(message=="") message = "Esta seguro que desea eliminar este anticipo?"
        //console.log("Separar ID: "+value);
        if (confirm(message)){
        obref = document.querySelector(".productItemid[value='"+id_producto+"']").parentNode;
        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");
        myHeaders.append("Accept", "application/json, text-plain, */*");
        myHeaders.append("X-Requested-With", "XMLHttpRequest");
        //"X-CSRF-TOKEN": token
        var c_token = MD5('?value'+value);//ojo en backend debe ser igual
        var _token ='{{ csrf_token() }}';
        var params = '?value='+value+'&c_token='+c_token+'&_token='+_token;
        var url = '{{ route('dsi.desaociateProductAdvance') }}'+params;
        var myInit = { method: 'get',
        headers: myHeaders,
        mode: 'same-origin',
       /* data: {
            value:value,
            _token:_token,
            _method:'post'
        },
        */
        cache: 'default' };
      
        var myRequest = new Request(url, myInit);
        fetch(myRequest)
        .then(function(data) {
            return data.json().then(function(json) { 
                console.log(json);
                if (json.success){
                    list_products_with_advances(obref);
                    alert(json.message);
                }else{
                    console.log(json.message);
                }
            });
        
        });
        }
    }
    function asociarProductoAnticipo(producto,value, anticipo,mensaje=""){
        <?php
        /**
         * Funcion asociarProductoAnticipo en JavaScript, queda pendiente a replantear con método post
         * Se usa el método GET con token de seguridad de sesión en los parámetros
         */
        ?>
        if(mensaje=="") mensaje="Confirma que desea utilizar el anticipo?";
        if(confirm(mensaje)){
        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");
        myHeaders.append("Accept", "application/json, text-plain, */*");
        myHeaders.append("X-Requested-With", "XMLHttpRequest");
        
        //Paràmetros
        var _token ='{{ csrf_token() }}';
        var c_token = MD5('?p='+producto+'&a='+anticipo);//ojo en backend debe ser igual
        var i = '{{ $dia_iva->dsi_id }}';
        var d = '{{ $dia_iva->id }}';
        var params = '?i='+i+'&d='+d+'&p='+producto+'&a='+anticipo+'&v='+value+'&c_token='+c_token+'&_token='+_token;
        var url = '{{ route('dsi.asociateProductAdvance') }}'+params;
        var myInit = { method: 'get',//pasar a post
        headers: myHeaders,
        mode: 'same-origin',
        /*
        body: {
            _token:_token,
            c_token:c_token,
            _method:'get',
            p:producto,
            a:anticipo,
            i:i,
            d:d
        },
        */
        cache: 'default' };
     
        

        var myRequest = new Request(url, myInit);
        var loading = document.getElementById('loading');
        loading.style.display = 'block';  
        fetch(myRequest)
        .then(function(data) {
        var loading = document.getElementById('loading');
            loading.style.display = 'none';
            var contentType = data.headers.get("content-type");
            if(contentType && contentType.indexOf("application/json") !== -1) {
                var value_id_producto_para_anticipo = document.getElementById('id_producto_para_anticipo').value;
                obref = document.querySelector(".productItemid[value='"+value_id_producto_para_anticipo+"']").parentNode;
                list_products_with_advances(obref);
                return data.json().then(function(json) {
                var contenidoTablaAdvancesProductos = document.getElementById('empty_advances');
                contenidoTablaAdvancesProductos.innerHTML = '';
                var total = 0;
                json.data.forEach(obj => {
                    total = total + obj.valor_recibo;
                    contenidoTablaAdvancesProductos.innerHTML += `<tr>
                    <td>${obj.num_recibo}</td>
                    <td>${obj.fecha_recibo}</td>
                    <td>$ ${ custom_currency_format(obj.valor_recibo) }</td>
                    <td>$ ${ custom_currency_format(obj.saldo) }</td>
                    <td><input type="number" min="0" max="${ obj.saldo }" class="form-control valor_anticipo_usar_en_producto" value="0"></td>
                    <td><span class="saldo_anticipo_usar_en_producto">$ ${ custom_currency_format(obj.saldo) }</span></td>
                    <td>
                        <button scr type="button" 
                        onclick="asociarProductoAnticipo(document.getElementById('id_producto_para_anticipo').value,this.parentNode.parentNode.querySelector('.valor_anticipo_usar_en_producto').value,${obj.id},'Confirma que desea utilizar el anticipo de $ '+ custom_currency_format(this.parentNode.parentNode.querySelector('.valor_anticipo_usar_en_producto').value) +' en el producto con referencia '+ document.getElementById('ref_producto_para_anticipo').value +'?')" class="btn btn-success">Seleccionar</button>
                    </td>
                    <tr>`;
                });           
                var footerTablaAdvancesProductos = document.getElementById('total_advances');
                footerTablaAdvancesProductos.innerHTML = '';
            
                if (total != 0){
                    footerTablaAdvancesProductos.innerHTML = `<tr>
                        <td></td>
                        <td>Total</td>
                        <td>$ ${ custom_currency_format(total) }</td>
                        <td></td>
                        <td></td>
                    </tr>`;
                }
                load_saldo_auto();
                alert(json.message);
                });
            }else{
            console.log("Error, no se consultaron datos!");
            }
        });
    }
}
</script>
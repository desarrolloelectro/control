<!-- Modal -->
<div class="modal fade" id="modalbuscarProductos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Seleccionar Productos</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <form id="buscarProductoForm" onsubmit="return false">
        @csrf
        {{ method_field('GET') }}
        <div class="col-md-3" style="display: block;">
            <div class="form-group">
                <label>Buscar</label>    
                <div class="input-group">
                    <input required="" type="text" class="form-control" id="valuebuscarproducto" name="value" value="">
                    <div class="input-group-append">
                        <span class="input-group-text fa fa-search" id="basic-addon2"  onclick="buscarProducto()" title="Buscar"></span>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <input type="hidden" id="refs" value="">
        <input type="hidden" id="refp" value="">
        <input type="hidden" id="page" value="1">
        <div class="contenido-modal">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Referencia</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Línea</th>
                            <!--th>Valor</th-->
                        </tr>
                    </thead>
                    <tbody id="contenidoTablaProductos">        
                    </tbody>
                </table>
                <div id="loading" style="display:none;">
                  Cargando...
                  <img style="width: 30px;" src="{{ asset('dashboard/img/cargando.gif') }}" />
                <div>
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>
</div>
<style>
  table td{
    cursor: pointer !important;
  }
</style>
<script>
  window.addEventListener('DOMContentLoaded', (event) => {
    var nodebuscarproducto = document.getElementById('valuebuscarproducto');
    nodebuscarproducto.addEventListener("keydown", function (e) {
      if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        buscarProducto();
      }
    });
  });
  
  function rellenarProducto(nombre, referencia, valor, linea){   
    var sep = document.getElementById('separados');
    var refs_val = document.getElementById('refs').value;
    var refp_val = document.getElementById('refp').value;
    var productItem = sep.children[refs_val].querySelector(".products").children[refp_val];
    productItem.querySelector(".nombre").value=atob(nombre);
    productItem.querySelector(".referencia").value=atob(referencia);
    //productItem.querySelector(".valor").value=atob(valor);
    productItem.querySelector(".serial").focus();
    productItem.querySelector(".linea").value=atob(linea);
    convertirnumeroALetrasAll(productItem.querySelector(".valor"));
  }
  function buscarProducto(){
    var form = new FormData(document.getElementById('buscarProductoForm'));
    //var contenidoTablaProductos = document.getElementById('contenidoTablaProductos');
    var valuebuscarproducto = document.getElementById('valuebuscarproducto').value;
    var page = document.getElementById('page').value;
    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    
    var myInit = { method: 'GET',
      headers: myHeaders,
      mode: 'same-origin',
      cache: 'default' };
      
      var myRequest = new Request('{{ route('dsi.data.buscarProductos') }}?value='+valuebuscarproducto+'&page='+page, myInit);
    var loading = document.getElementById('loading');
    loading.style.display = 'block';  
    fetch(myRequest)
    .then(function(data) {
      var loading = document.getElementById('loading');
        loading.style.display = 'none';
        var contentType = data.headers.get("content-type");
        if(contentType && contentType.indexOf("application/json") !== -1) {
          return data.json().then(function(json) {
          var contenidoTablaProductos = document.getElementById('contenidoTablaProductos');
          contenidoTablaProductos.innerHTML = '';
          json.productos.data.forEach(obj => {
            contenidoTablaProductos.innerHTML += `<tr data-dismiss="modal" title="Haga clic para seleccionar este producto" onclick="rellenarProducto('${btoa(obj.nombre)}', '${btoa(obj.referencia)}', '${btoa(obj.valor)}', '${btoa(obj.linea)}');">
              <td>${obj.id}</td>
              <td>${obj.referencia}</td>
              <td>${obj.nombre}</td>
              <td>${obj.marca}</td>
              <td>${obj.linea}</td>
            <tr>`;//<td style="text-align:right;">$ ${ custom_currency_format(obj.valor) }</td>
          });
          //console.log(json);
          contenidoTablaProductos.innerHTML += `<tr><td colspan="6">${json.pagination}<td><tr>`;
        });
        } else {
        console.log("Oops, we haven't got data!");
        }
    });
    }
</script>

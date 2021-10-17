@extends('layout_login')

@section('contenido')


 

<header>
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
          <!-- Slide One - Set the background image for this slide in the line below -->
            <div class="carousel-item active" style="background-image: url('{{ asset('images/') }}{{"/img1.jpg"}}')">
                <div class = "cover-carousel"></div>
                <div class="carousel-caption d-md-block">
                    <h3>Bienvenid@ a</h3>
                    <h5><span class = "rojo">T</span>oolset Control</h5>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/') }}{{"/img2.jpg"}}')">
                <div class = "cover-carousel"></div>
                <div class="carousel-caption d-md-block">
                    <h3>Bienvenid@ a</h3>
                    <h5><span class = "rojo">T</span>oolset Control</h5>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('{{ asset('images/') }}{{"/img3.jpg"}}')">
                <div class = "cover-carousel"></div>
                <div class="carousel-caption d-md-block">
                    <h3>Bienvenid@ a</h3>
                    <h5><span class = "rojo">T</span>oolset Control</h5>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
</header>

<div class = "container container-herramientas">
	<div class="card">
		  <div class="card-body">
		  	<div class = "row">
              <div class = "col-md-4"></div>
              <div class = "col-md-4">
                <center>
                        <img class = "imagen-login"  src="{{ asset('images/') }}{{"/logo2.png"}}">
                </center>
                <form class="form-auth-small"  method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <center><h4>INICIAR SESIÓN</h4></center>
                    <div class="form-group {{ $errors->has('coduser') ? ' has-error' : '' }}">
                        <label for="coduser" class="control-label">Ingrese su usuario</label>
                        <input required id="coduser" type="text" class="form-control" name="coduser" value="{{ old('coduser') }}" autofocus placeholder="Usuario">
                        @if ($errors->has('coduser'))
                            <span class="help-block">
                                {{ $errors->first('coduser') }}
                            </span>
                        @endif
                    </div> 
                    <div class="form-group {{ $errors->has('contrasena') ? ' has-error' : '' }}">
                        <label for="contrasena" class="control-label">Ingrese su contraseña</label>
                        <input required type="password" class="form-control" id="contrasena" name = "contrasena" value="" placeholder="Contraseña">
                        @if ($errors->has('contrasena'))
                            <span class="help-block">
                                {{ $errors->first('contrasena') }}
                            </span>
                        @endif
                    </div>
                    <center>
                        <button type="submit" class="btn btn-danger btn-rojo">INGRESAR</button>
                        <a href="http://mitoolset.com/" class="btn btn-dark mt-1 mb-1"><i class="fa fa-arrow-left" aria-hidden="true"></i> REGRESAR</a>
                    </center>
                    
                    @if(session()->has('mensaje'))
                    <br>
                        <div class="alert alert-danger">
                            {{ session()->get('mensaje') }}
                        </div>
                    @endif
                </form>  
              </div>
              <div class = "col-md-4"></div>
		  	</div>
		  </div>
          <div class = "row row-marcas">
            <div class = "col-md-3 col-marcas">
                <center>
                <img  src="{{ asset('images/electro1.png') }}">
                </center>
            </div>
            <div class = "col-md-3 col-marcas">
                <center>
                <img  src="{{ asset('images/keeway.png') }}">
                </center>
            </div>
            <div class = "col-md-3 col-marcas">
                <center>
                <img  src="{{ asset('images/benelli.png') }}">
                </center>
            </div>
            <div class = "col-md-3 col-marcas">
                <center>
                <img  src="{{ asset('images/interelec.png') }}">
                </center>
            </div>
          </div>
	</div>
</div>

@endsection


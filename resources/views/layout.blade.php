<!DOCTYPE html>

<html lang="es">

  <head>

    <!-- Open Graph Meta-->

    <title>Toolset  Control</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Main CSS-->

    <link rel="stylesheet" type="text/css" href="{{ asset('docs/css/main.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('docs/css/estilos_electro.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('docs/css/font-awesome.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('docs/css/fonts.googleapis.com.css')}}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />



    <!-- Font-icon css-->

    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/favicon.png') }}">

  

	<script src="{{ asset('docs/js/jquery-3.2.1.min.js')}}"></script>



  </head>

  <body class="app sidebar-mini rtl pace-done {{ (isset($_COOKIE['statesidebar']) && $_COOKIE['statesidebar'] =='true') ? 'sidenav-toggled' : '' }}">

    <!-- Navbar-->

    <header class="app-header"><a class="app-header__logo" href="{{ route('principal.index') }}">

				<img style = "width:140px!important;margin-top:7px;" src="{{ asset('images/logo2.png') }}" alt="">

    </a>

      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" onclick="toogleCookie('statesidebar');" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>

      <!-- Navbar Right Menu-->

      <ul class="app-nav">

        <!-- User Menu-->

        <li class="app-search color-blanco">
        
        </li>



        

        <li class="dropdown"><a class="app-nav__item sin-decoracion" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><span id = "nombre-usuario"><?php if (Auth::id() != null) echo Auth::user()->nombre;?> </span><i class="fa fa-user fa-lg"></i></a>

        

          <ul class="dropdown-menu settings-menu dropdown-menu-right">

            @if(Auth::id() != 'CONSULT')

            <li><a class="dropdown-item" href="{{ route('usuarios.perfil') }}"><i class="fa fa-user fa-lg"></i> Perfil</a></li>

            @endif

            <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();

									document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-lg"></i> Salir</a></li>

          </ul>

        </li>

      </ul>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

			{{ csrf_field() }}

		</form>
    
    {!! banda_mantenimiento() !!}
    
    </header>

    <!-- Sidebar menu-->

    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>

    <aside class="app-sidebar">

      <ul class="app-menu">

        <li><a class="app-menu__item <?php if (isset($controlador) && $controlador == 'principal') echo 'active';?>" href="{{ route('principal.index') }}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Principal</span></a></li>

        <li><a class="app-menu__item <?php if (isset($controlador) && $controlador == 'cotizaciones') echo 'active';?>" href="{{ route('cotizaciones.index') }}"><i class="app-menu__icon fa fa-check-square-o"></i><span class="app-menu__label"> Autorizaciones / <br>Gastos</span></a></li>

        <li><a class="app-menu__item <?php if (isset($controlador) && $controlador == 'dsi') echo 'active';?>" href="{{ route('dsi.index') }}"><i class="app-menu__icon fa fa-shopping-bag"></i><span class="app-menu__label">Días sin IVA</span></a></li>

		    <li><a class="app-menu__item <?php if (isset($controlador) && $controlador == 'reportes') echo 'active';?>" href="{{ route('reportes.index') }}"><i class="app-menu__icon fa fa-bar-chart"></i><span class="app-menu__label">Reportes</span></a></li>

        <li><a class="app-menu__item <?php if (isset($controlador) && $controlador == 'configuracion') echo 'active';?>" href="{{ route('institucion.index') }}"><i class="app-menu__icon fa fa-cog"></i><span class="app-menu__label">Configuración</span></a></li>

  </ul>

    </aside>

    <main class="app-content">



	  @yield('content')





    </main>

    <!-- Essential javascripts for application to work-->



    <script src="{{ asset('docs/js/popper.min.js')}}"></script>

    <script src="{{ asset('docs/js/bootstrap.min.js')}}"></script>

    <script src="{{ asset('docs/js/main.js')}}"></script>
    <script src="{{ asset('docs/js/cookies.js')}}"></script>

    <!-- The javascript plugin to display page loading on top-->

    <script src="{{ asset('docs/js/plugins/pace.min.js')}}"></script>

	<script src="{{ asset('docs/js/bootbox.js')}}"></script>

    <!-- Page specific javascripts-->

    <!-- Data table plugin-->

    <script type="text/javascript" src="{{ asset('docs/js/plugins/jquery.dataTables.min.js')}}"></script>

    <script type="text/javascript" src="{{ asset('docs/js/plugins/dataTables.bootstrap.min.js')}}"></script>

    <script type="text/javascript" src="{{ asset('docs/js/plugins/select2.min.js')}}"></script>



    <!-- Page specific javascripts-->

    <!-- Google analytics script-->

    <script type="text/javascript">



    </script>

  </body>

</html>




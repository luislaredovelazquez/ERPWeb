<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>QuimeraSystems</title>

	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    
	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Navegación</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}">QuimeraSystems</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
					    <li><a href="{{ url('/createWebPage') }}">Crear una página web</a></li>
						<li><a href="{{ url('/auth/register') }}">Registrarse</a></li>
						<li><a href="{{ url('/auth/login') }}">Ingresar</a></li>						
					@else
					   <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Clientes<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('clients/create') }}">Crear Clientes</a></li>
								<li><a href="{{ url('clients/show') }}">Consultar Clientes</a></li>
							</ul>
						</li>
						
	                  <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Artículos<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('articles/create') }}">Crear Artículos</a></li>
								<li><a href="{{ url('articles/show') }}">Consultar Artículos</a></li>
							</ul>							
						</li>
					   
					   <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Facturación<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('invoices/create') }}">Crear Facturas</a></li>
								<li><a href="{{ url('invoices/createReturn') }}">Crear Notas de Crédito</a></li>
								<li><a href="{{ url('invoices/show') }}">Consultar Facturación</a></li>
								<li><a href="{{ url('invoices/showFormats') }}">Seleccionar un Formato</a></li>
							</ul>							
						</li>
						
			           <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Avisos<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('reminders/create') }}">Aviso de Cobro</a></li>
								<li><a href="{{ url('reminders/birthday') }}">Felicitación de Cumpleaños</a></li>
								<li><a href="{{ url('reminders/voice') }}">Aviso de Voz</a></li>
								<li><a href="{{ url('reminders/open') }}">Aviso Libre</a></li>
								<li><a href="{{ url('reminders/show') }}">Consulta tus Avisos</a></li>
							</ul>							
						</li>
					   
					   				
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							<li><a href="{{ url('editProfile') }}">Mi perfil</a></li>	
							<li><a href="{{ url('changePassword') }}">Cambiar Contraseña</a></li>	
							<li><a href="{{ url('editFiscalInfo') }}">Mi Información Fiscal</a></li>
							<li><a href="{{ url('editFile') }}">Mis Archivos</a></li>
							<li><a href="{{ url('shop/show') }}">Mis compras</a></li>							
							<li><a href="{{ url('/auth/logout') }}">Salir</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
    <div class="container">
	@yield('content')
	
     

      <!-- Site footer -->  
    </div>
<!--    
 <footer class="footer">
        <p>&copy; 4Uno</p>
 </footer> 
-->

	<!-- Scripts -->
  	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	
	@yield('js')
</body>
</html>

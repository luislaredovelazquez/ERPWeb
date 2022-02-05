@extends('app')

@section('content')	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Mis archivos</div>
				<div class="panel-body">
						@include('errors.list')
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/updateFiles') }}" enctype="multipart/form-data">
						<input name="_method" type="hidden" value="PATCH">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Número de Certificado</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="num_certificado" value="{{ $usuario -> num_certificado }}">
							</div>
						</div>
						
	   <div class="form-group">
		<label class="col-md-4 control-label">Logo para tus facturas</label>
			<div class="col-md-6">				
                        <input type="file" name="fileLogo">
			    </div>
			</div>                        
	   <div class="form-group">
		<label class="col-md-4 control-label">Certificado de Sello Digital (.cer)</label>
			<div class="col-md-6">                        
                        <input type="file" name="fileCer">
			    </div>
			</div>                        
	   <div class="form-group">
		<label class="col-md-4 control-label">Archivo de llave privada (.key)</label>
			<div class="col-md-6">                        
                        <input type="file" name="fileKey">
			    </div>
			</div>                        
                        <div class="form-group">
							<label class="col-md-4 control-label">Contraseña Key</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="contrasena" value="{{ $usuario -> contrasena }}">
							</div>
						</div>


						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Actualizar archivos
								</button>
							</div>
						</div>
					</form>
					

</div>
</div>
</div>
</div>

@stop
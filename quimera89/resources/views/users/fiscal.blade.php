@extends('app')

@section('content')	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Información Fiscal</div>
				<div class="panel-body">
						@include('errors.list')
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/updateFiscal') }}">
						<input name="_method" type="hidden" value="PATCH">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						
				      <div class="form-group">
							<label class="col-md-4 control-label">RFC</label>
							<div class="col-md-6">
								<input type="cellphone" class="form-control" name="rfc" value="{{ $usuario -> rfc }}" placeholder="Escribe aquí tu RFC asignado por el SAT">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Régimen Fiscal</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="regimen" value="{{ $usuario -> regimen }}" placeholder="¿Cuál es tu régimen fiscal actual?">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Código Postal</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="codigoPostal" value="{{ $usuario -> codigoPostal }}" placeholder="¿Con qué código postal te registraste?">
							</div>
						</div>	
						
						<div class="form-group">
							<label class="col-md-4 control-label">País</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="pais" value="{{ $usuario -> pais }}" placeholder="¿Cuál es el país que aparece en tu registro?">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Estado</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="estadoRepublica" value="{{ $usuario -> estadoRepublica }}" placeholder="¿Cuál es el estado que aparece en tu registro?">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Municipio</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="municipio" value="{{ $usuario -> municipio }}" placeholder="¿Cuál es el municipio que aparece en tu registro?">
							</div>
						</div>
												
						<div class="form-group">
							<label class="col-md-4 control-label">Localidad</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="localidad" value="{{ $usuario -> localidad }}" placeholder="¿Cuál es la localidad que aparece en tu registro?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Colonia</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="colonia" value="{{ $usuario -> colonia }}" placeholder="¿Cuál es la colonia que aparece en tu registro?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Número Interior</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="noInterior" value="{{ $usuario -> noInterior }}" placeholder="¿Con qué número interior que aparece tu registro?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Número Exterior</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="noExterior" value="{{ $usuario -> noExterior }}" placeholder="¿Con qué número exterior que aparece tu registro?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Calle</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="calle" value="{{ $usuario -> calle }}" placeholder="¿Cuál es la calle que aparece en tu registro?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Expedido Código Postal</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="e_codigoPostal" value="{{ $usuario -> e_codigoPostal }}" placeholder="¿En qué código postal expedirás tus facturas?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Expedido País</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="e_pais" value="{{ $usuario -> e_pais }}" placeholder="¿En qué país expedirás tus facturas?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Expedido Estado</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="e_estado" value="{{ $usuario -> e_estado }}" placeholder="¿En qué estado expedirás tus facturas?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Expedido Municipio</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="e_municipio" value="{{ $usuario -> e_municipio }}" placeholder="¿En qué municipio expedirás tus facturas?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Expedido Localidad</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="e_localidad" value="{{ $usuario -> e_localidad }}" placeholder="¿En qué localidad expedirás tus facturas?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Expedido Colonia</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="e_colonia" value="{{ $usuario -> e_colonia }}" placeholder="¿En qué colonia expedirás tus facturas?">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Expedido Número Interior</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="e_noInterior" value="{{ $usuario -> e_noInterior }}" placeholder="¿Con qué número interior expedirás tus facturas?">
							</div>
						</div>
						
												
						<div class="form-group">
							<label class="col-md-4 control-label">Expedido Número Exterior</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="e_noExterior" value="{{ $usuario -> e_noExterior }}" placeholder="¿Con qué número exterior expedirás tus facturas?">
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-md-4 control-label">Expedido Calle</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="e_calle" value="{{ $usuario -> e_calle }}" placeholder="¿En qué calle expedirás tus facturas?">
							</div>
						</div>


						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Actualizar mi información fiscal
								</button>
							</div>
						</div>
					</form>
					

</div>
</div>
</div>
</div>

@stop
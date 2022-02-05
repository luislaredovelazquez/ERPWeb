@extends('app')

@section('content')		

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Cambia tu contraseña</div>
				<div class="panel-body">
						@include('errors.list')
	
	                  {!! Form::open(['method' => 'PATCH','action' => 'UserController@updatePwd']) !!}				
					  
					  
					   <div class="form-group">
							<label for="old_password">Escribe tu contraseña anterior</label>
						    <input type="password" class="form-control" name="old_password" id="old_password">
						</div>

						<div class="form-group">
							<label for="password">Contraseña Nueva</label>
							<input type="password" class="form-control" name="password" id="password">
						</div>

						<div class="form-group">
							<label for="password_confirmation">Confirma tu contraseña</label>
							<input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
						</div>

						
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Cambiar Contraseña
								</button>
							</div>
						</div>
						
   {!! Form::close() !!}	
   				
</div>
</div>
</div>
</div>			
   				
   					
@stop



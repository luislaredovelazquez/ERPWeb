@extends('app')

@section('content')

<h3>Actualiza la cuenta de:</h3><h4>{!! $cliente -> nombrecompleto !!}</h4>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Actualizar</div>
				<div class="panel-body">

@include('errors.list')

{!! Form::model($cliente,['method' => 'PATCH','action' => ['ClientController@update',$cliente -> id]]) !!}

@include('clients.form',['SubmitButtonText' => 'Actualizar Cuenta'])

{!! Form::close() !!}


</div>
</div>
</div>
</div>
</div>
</div>

@stop
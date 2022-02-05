@extends('app')

@section('content')

<h3>Registra a un nuevo cliente</h3>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Registrar</div>
				<div class="panel-body">

@include('errors.list')

{!! Form::open(['url' => 'clients']) !!}

@include('clients.form',['SubmitButtonText' => 'Registrar'])

{!! Form::close() !!}

</div>
</div>
</div>
</div>
</div>
</div>

@stop
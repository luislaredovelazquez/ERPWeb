@extends('app')

@section('content')

<h3>Crea una nuevo artículo</h3>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Crear</div>
				<div class="panel-body">

@include('errors.list')

{!! Form::open(['url' => 'articles']) !!}

@include('articles.form',['SubmitButtonText' => 'Crear Artículo'])

{!! Form::close() !!}

</div>
</div>
</div>
</div>
</div>
</div>

@stop
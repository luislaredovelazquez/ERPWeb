@extends('app')

@section('content')

<h3>Actualiza el artículo:</h3><h4>{!! $articulo -> codigo !!}</h4>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Actualizar</div>
				<div class="panel-body">

@include('errors.list')

{!! Form::model($articulo,['method' => 'PATCH','action' => ['ArticleController@update',$articulo -> id]]) !!}

@include('articles.form',['SubmitButtonText' => 'Actualizar Artículo'])

{!! Form::close() !!}


</div>
</div>
</div>
</div>
</div>
</div>

@stop
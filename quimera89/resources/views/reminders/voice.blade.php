@extends('app')

@section('content')

<h3>Recordatorio</h3>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Recordatorio</div>
				<div class="panel-body">
<audio controls src="{{ $recordatorio }}" style="width: 250px;"></audio>
</div>
</div>
</div>
</div>
</div>
</div>

@stop
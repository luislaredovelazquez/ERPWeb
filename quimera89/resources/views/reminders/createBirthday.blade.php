@extends('app')

@section('content')

<h3>Crea un aviso</h3>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Felicitación de Cumpleaños</div>
				<div class="panel-body">

@include('errors.list')

{!! Form::open(['url' => 'reminders/storeBirthday']) !!}

{!! Form::hidden('tipo', '2') !!}

<h3>Envío</h3>
<div class="form-group">	
{!! Form::label('c_sms','¿Cómo deseas enviar este mensaje?') !!}
{!! Form::select('c_sms',['0' => 'Por SMS','1' => 'Por Email'],
                          null,['class' => 'form-control']) !!}
</div>

<h3>Usuario</h3>
<div class="form-group">	
{!! Form::label('c_cliente','Usuario') !!}
{!! Form::select('c_cliente',$clientes,
                          null,['class' => 'form-control']) !!}
</div>


<h3>Tiempo</h3>

<div class="form-group">	
{!! Form::label('c_hora','¿A qué hora deseas mandar este aviso?') !!}
{!! Form::select('c_hora',['8' => '8 am','9' => '9 am','10' => '10 am','11' => '11 am','12' => '12 pm',
                           '13' => '1 pm','14' => '2 pm','15' => '3 pm','16' => '4 pm','17' => '5 pm',
                           '18' => '6 pm','19' => '7 pm'],
                          '18',['class' => 'form-control']) !!}
</div>

<div class="form-group">
{!! Form::label('fechainicio','Pon aquí la fecha de cumpleaños') !!}
{!! Form::text('fechainicio',null,['class' => 'datepicker form-control','readonly' => 'readonly']) !!}
</div>

<div class="form-group">	
{!! Form::label('lapso','¿Cada cuánto tiempo deseas que se envíen los avisos?') !!}
{!! Form::select('lapso',['1' => 'Una sola vez','7' => 'Cada año'],
                          null,['class' => 'form-control']) !!}
</div>
<div class="form-group">
{!! Form::label('fechafinal','¿Cuando deseas que se mande el último aviso?') !!}
{!! Form::text('fechafinal',null,['class' => 'datepicker form-control','readonly' => 'readonly']) !!}
</div>

<div class="form-group">
{!! Form::submit('Crear',['class' => 'btn btn-primary form-control']) !!}
</div>

{!! Form::close() !!}

</div>
</div>
</div>
</div>
</div>




@stop

@section('js')

<link href="{{ asset('assets/css/jquery-ui.css') }}" rel="stylesheet">

<!-- Zona JS -->
<script src="{{ asset('assets/plugins/jquery-1.10.2.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-ui.js') }}"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){
	
	$('#c_cliente').select2();
	
	  });

  $(function() {
  	
  	
 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '<Ant',
 nextText: 'Sig>',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);  	
  	
  	
 $( ".datepicker" ).datepicker({ dateFormat: 'yy/mm/dd' });
    
    
    var meses = new Array("01", "02", "03", 
    "04", "05", "06", "07", "08", "09", 
    "10", "11", "12");
    
    
    var today = new Date();
	var ahora = new Date(today);
	ahora.setDate(today.getDate()+1);
    
    var curr_date = ahora.getDate();
    var curr_month = ahora.getMonth();
    var curr_year = ahora.getFullYear();
    
    document.getElementById('fechainicio').value = curr_year+ "/"+meses[curr_month] + "/" + curr_date;
    document.getElementById('fechafinal').value = curr_year+ "/"+meses[curr_month] + "/" + curr_date;
  });
 
</script>

@stop
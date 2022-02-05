@extends('app')

@section('content')

<h3>Crea un aviso</h3>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Aviso de Cobro</div>
				<div class="panel-body">

@include('errors.list')

{!! Form::open(['url' => 'reminders']) !!}

{!! Form::hidden('tipo', '1') !!}

@include('reminders.form',['SubmitButtonText' => 'Crear'])

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
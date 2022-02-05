@extends('app')

@section('content')

<h3>Crea un aviso</h3>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Aviso de Voz</div>
				<div class="panel-body">

@include('errors.list')

{!! Form::open(['url' => 'reminders/storeVoice', 'enctype' => 'multipart/form-data']) !!}

{!! Form::hidden('tipo', '3') !!}
{!! Form::hidden('intervalo', '0',['id' => 'intervalo']) !!}

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




<hr />

	 <audio style="width: 250px;" controls src="" id="audio"></audio>
      <div style="margin:10px;">
        <a class="button" id="record" onclick="iniciar();">Grabar</a>
        <a class="button disabled one" id="play">Parar</a>
      </div>
      
      <style>
      .button{
        display: inline-block;
        vertical-align: middle;
        margin: 0px 5px;
        padding: 5px 12px;
        cursor: pointer;
        outline: none;
        font-size: 13px;
        text-decoration: none !important;
        text-align: center;
        color:#fff;
        background-color: #4D90FE;
        background-image: linear-gradient(top,#4D90FE, #4787ED);
        background-image: -ms-linear-gradient(top,#4D90FE, #4787ED);
        background-image: -o-linear-gradient(top,#4D90FE, #4787ED);
        background-image: linear-gradient(top,#4D90FE, #4787ED);
        border: 1px solid #4787ED;
        box-shadow: 0 1px 3px #BFBFBF;
      }
      a.button{
        color: #fff;
      }
      .button:hover{
        box-shadow: inset 0px 1px 1px #8C8C8C;
      }
      .button.disabled{
        box-shadow:none;
        opacity:0.7;
      }
      </style>

<input type="hidden" value="" id="audioGuardado" name="audioGuardado" />


<h3>Tiempo</h3>
<div class="form-group">	
{!! Form::label('c_hora','¿A qué hora deseas mandar este aviso?') !!}
{!! Form::select('c_hora',['8' => '8 am','9' => '9 am','10' => '10 am','11' => '11 am','12' => '12 pm',
                           '13' => '1 pm','14' => '2 pm','15' => '3 pm','16' => '4 pm','17' => '5 pm',
                           '18' => '6 pm','19' => '7 pm'],
                          '18',['class' => 'form-control']) !!}
</div>

<div class="form-group">
{!! Form::label('fechainicio','¿Cuando deseas mandar el primer aviso?') !!}
{!! Form::text('fechainicio',null,['class' => 'datepicker form-control','readonly' => 'readonly']) !!}
</div>

<div class="form-group">	
{!! Form::label('lapso','¿Cada cuánto tiempo deseas que se envíen los avisos?') !!}
{!! Form::select('lapso',['1' => 'Una sola vez','2' => 'Cada semana','3' => 'Cada quincena','4' => 'Cada mes'
						 ,'5' => 'Cada bimestre','6' => 'Cada semestre','7' => 'Cada año'],
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

<script src="{{ asset('assets/plugins/recorder.js') }}"></script>
<script src="{{ asset('assets/plugins/recorderWorker.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery.voice.js') }}"></script>
<script src="{{ asset('assets/plugins/record.js') }}"></script>

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
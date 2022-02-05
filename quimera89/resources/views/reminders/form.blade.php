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



<h3>Descripción</h3>

<div id="cobro">
<div class="form-group">
{!! Form::label('monto','Monto') !!}
{!! Form::text('monto',null,['class' => 'form-control','placeholder' => 'El monto que deseas que pague ej. 20.00']) !!}
</div>

<div class="form-group">
{!! Form::label('motivo','Concepto') !!}
{!! Form::text('motivo',null,['class' => 'form-control','placeholder' => 'El motivo del cobro, ej. Renta']) !!}
</div>
</div>

<div class="form-group">
{!! Form::submit($SubmitButtonText,['class' => 'btn btn-primary form-control']) !!}
</div>
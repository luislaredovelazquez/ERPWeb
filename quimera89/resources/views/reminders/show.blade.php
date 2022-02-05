@extends('app')

@section('content')

<div class="row">
@if($recordatorios == 0)
<ul class="alert alert-danger">
	<li>Usted no tiene timbres disponibles para enviar avisos por SMS</li>	
</ul>
@endif
	
<div class="col-md-4"><h3>Avisos disponibles: {{ $recordatorios }}</h3></div>	
<div class="col-md-4  col-md-offset-4" style="text-align: right;"><h5><a style="cursor: pointer;" onclick="mostrarForma();">Comprar recordatorios</a></h5></div>	
</div>
@if($errors -> any())
<div class="row" id="comprarFolios" style="display:inline;">
@else	
<div class="row" id="comprarFolios" style="display:none;">
@endif	
 	<div class="col-md-8 col-md-offset-2 alert alert-info alert-dismissible" role="alert">
 	<!--  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
 	  <span aria-hidden="true">&times;</span>
 	  </button>-->
 	@include('errors.list')  	
	<div class="panel-body">
	{!! Form::open(['url' => 'shop/manageReminder']) !!}
	<div class="form-group">
	{!! Form::label('pedido','Quiero comprar ') !!}
    {!! Form::select('pedido',['7' => '7 Avisos','20' => '20 Avisos','40' => '40 Avisos',
    						   '60' => '60 Avisos','80' => '80 Avisos',
                               '100' => '100 Avisos','200' => '200 Avisos',
                               '1000' => '1000 Avisos','10000' => '10000 Avisos'],null,['class' => 'form-control','id' => 'pedido' ]) !!}
    </div>
    <label class="col-md-4 control-label">Subtotal: <span id="c_subtotal">40.00</span></label>
    <label class="col-md-4 control-label">Iva: <span id="c_iva">6.40</span></label>
    <label class="col-md-4 control-label">Total: <span><b  id="c_total">56.80</b></span></label>
    
    <div class="form-group">
	{!! Form::label('pwd','Para confirmar tu compra escribe tu contraseña') !!}
    <input type="password" class="form-control" name="pwd">
    </div>
    
    <div class="form-group">
	{!! Form::submit("Comprar",['class' => 'btn btn-primary form-control']) !!}
	</div>
	{!! Form::close() !!}
	</div>
 </div>
</div>

<br />


          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Tipo</th>
                  <th>Cliente</th>	
                  <th>Fecha de Inicio</th>
                  <th>Próximo Aviso</th>
                  <th>Fecha Final</th>
                  <th>Cancelar</th>    
                </tr>
              </thead>
              <tbody>
              	<?php $i=1; ?>
              	@foreach($resultados as $value)
                <tr>
                @if( $value->tipo  == '1')	
                  <td>Cobro</td>
                @elseif( $value->tipo  == '2')
                  <td>Cumpleaños</td>
                @elseif( $value->tipo  == '3')
                  <td>Voz</td>
                @elseif( $value->tipo  == '4')
                  <td>Abierto</td>
                @endif  
                  <td><a href="/reminders/{{ $value->id }}/edit">{{ $value->nombrecompleto }}</a></td>
                  <td>{{ $value->fechainicio }}</td>
                  <td>{{ $value->fechaactual }}</td>
                  <td>{{ $value->fechafinal }}</td>
                  @if($value->status == '0')
                  <td><a href="/reminders/{{ $value->id }}/cancel">Cancelar</a></td>
                  @elseif($value->status == '1')
                  <td><b>Cancelado</b></td>
                  @elseif($value->status == '2')
                  <td><b>Completado</b></td>
                  @endif                 
                 @endforeach 
                </tr>
              </tbody>
            </table>
          </div>

         <div class="row" style="text-align: center;">
         <?php echo $resultados->render(); ?>
         </div>

@stop

@section('js')

<script type="text/javascript">

var subtotal = 0;
var iva = 0;
var total = 0;

$(document).ready(function(){
	
	 subtotal = $('#pedido').val() * 2;
      $('#c_subtotal').text(subtotal.toFixed(2));
       iva = subtotal * 0.16;
      $('#c_iva').text(iva.toFixed(2));
       total = subtotal + iva;
      $('#c_total').text(total.toFixed(2));     
	
  $('#pedido').change(function(){   
  	  subtotal = $('#pedido').val() * 2;
      $('#c_subtotal').text(subtotal.toFixed(2));
       iva = subtotal * 0.16;
      $('#c_iva').text(iva.toFixed(2));
       total = subtotal + iva;
      $('#c_total').text(total.toFixed(2));     
  });
  });
  
 function mostrarForma()
 {
 if(document.getElementById('comprarFolios').style.display == 'none')	
 document.getElementById('comprarFolios').style.display = 'inline';
 else
 document.getElementById('comprarFolios').style.display = 'none';
 return true;	
 }
   
</script>  
@stop
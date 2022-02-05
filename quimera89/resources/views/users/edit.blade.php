@extends('app')

@section('content')	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Perfil de {{ $usuario -> email }}</div>
				<div class="panel-body">
						@include('errors.list')
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/updateProfile') }}" id="card-form">
						<input name="_method" type="hidden" value="PATCH">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Nombre</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $usuario -> name }}">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Teléfono (10 Dígitos)</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="telefono" value="{{ $usuario -> telefono }}" placeholder="5555000000">
							</div>
						</div>
						
						<!-- Datos de pago -->
						
						<hr />
<h4>Elige una opción de pago</h4>
<div class="btn-group" data-toggle="buttons">
@if($usuario->forma_pago == 0)	
  <label class="btn btn-default active"><input type="radio" value="0" name="opPago" id="opPago1" checked="">Pago en efectivo (OXXO)</label>  
  <label class="btn btn-default"><input type="radio" value="1" name="opPago" id="opPago2">Pago con tarjeta</label>
 @else
 <label class="btn btn-default"><input type="radio" value="0" name="opPago" id="opPago1">Pago en efectivo (OXXO)</label>  
 <label class="btn btn-default active"><input type="radio" value="1" name="opPago" id="opPago2" checked="">Pago con tarjeta</label>
 @endif 
</div>
<hr />
<span class="card-errors"></span>
   	
   <div class="form-group">
    
      <label class="col-md-4 control-label">Nombre del tarjetahabiente</label>
      <div class="col-md-6">
      <input type="text" id="tarjetahabiente" size="20" data-conekta="card[name]" class="form-control" placeholder="Como en tu tarjeta" disabled="true" />
      </div>
  </div>
  <div class="form-group">
    
      <label class="col-md-4 control-label">Número de tarjeta de crédito</label>
      <div class="col-md-6">
      <input type="text" id="tarjeta" size="20" data-conekta="card[number]" class="form-control" placeholder="4242 4242 4242 4242" disabled="true" />
      </div>
  </div>
  <div class="form-group">
    
      <label class="col-md-4 control-label">CVC</label>
      <div class="col-md-6">
      <input type="text" id="cvc" size="4" data-conekta="card[cvc]" class="form-control"  placeholder="1234" disabled="true" />
      </div>
  </div>
  <div class="form-group">
    
      <label class="col-md-4 control-label">Fecha de expiración (MM/AAAA)</label>
      <div class="col-md-6">
      <input type="text" id="fecha_mes" size="2" data-conekta="card[exp_month]" placeholder="MM" disabled="true" />
      <span>/</span>
      <input type="text" id="fecha_anio" size="4" data-conekta="card[exp_year]"  placeholder="AAAA" disabled="true" />
      </div>

  </div>
						
						<!-- Datos de pago -->

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Actualizar mi perfil
								</button>
							</div>
						</div>
					</form>
					

</div>
</div>
</div>
</div>

@stop

@section('js')
<script type="text/javascript" src="https://conektaapi.s3.amazonaws.com/v0.3.2/js/conekta.js"></script>

<script type="text/javascript">

 Conekta.setPublishableKey('key_QMmm9s2Yfaay7seztT9xqUA');  
 
 jQuery(function($) {
  $("#card-form").submit(function(event) {
    var $form;
    $form = $(this);
    
      if($('#tarjetahabiente').val() == ''&&$('#tarjeta').val() == ''&&$('#fecha_mes').val() == ''&&
      $('#fecha_anio').val() == ''&&$('#cvc').val() == ''||$('#opPago1').is(':checked'))
      {
      $form.append($("<input type=\"hidden\" name=\"conektaTokenId\" />").val(''));
      $form.get(0).submit();
      return false;
      }
    
    if($('#tarjetahabiente').val() == '')
    {
    html = "<ul class=\"alert alert-danger\"><li>El campo nombre está vacío</li></ul>";	
    $form.find(".card-errors").append(html);
    $form.find("button").prop("disabled", false);	
    return false;	
    }
    
    val1 = Conekta.card.validateNumber($('#tarjeta').val());
    if(val1 == false)
    {
    html = "<ul class=\"alert alert-danger\"><li>El formato de los números de la tarjeta es inválido</li></ul>";	
    $form.find(".card-errors").append(html);	
    $form.find("button").prop("disabled", false);	
    return false;	
    }
    
    val2 = Conekta.card.validateExpirationDate($('#fecha_mes').val(), $('#fecha_anio').val());
        if(val2 == false)
    {
    html = "<ul class=\"alert alert-danger\"><li>La fecha de expiración es inválida</li></ul>";	
    $form.find(".card-errors").append(html);	
    $form.find("button").prop("disabled", false);	
    return false;	
    }
    
    val3 = Conekta.card.validateCVC($('#cvc').val());
    if(val3 == false)
    {
    html = "<ul class=\"alert alert-danger\"><li>El código de seguridad no es un número entero de 3 a 4 caracteres</li></ul>";	
    $form.find(".card-errors").append(html);		
    $form.find("button").prop("disabled", false);	
    return false;	
    }
   
/* Previene hacer submit más de una vez */

    $form.find("button").prop("disabled", true);
    Conekta.token.create($form, conektaSuccessResponseHandler, conektaErrorResponseHandler);

    
/* Previene que la información de la forma sea enviada al servidor */

    return false;
  });
});

var conektaSuccessResponseHandler;
conektaSuccessResponseHandler = function(token) {
  var $form;
  $form = $("#card-form");

  
/* Inserta el token_id en la forma para que se envíe al servidor */

  $form.append($("<input type=\"hidden\" name=\"conektaTokenId\" />").val(token.id));

  
/* and submit */

  $form.get(0).submit();
};

var conektaErrorResponseHandler;
conektaErrorResponseHandler = function(response) {
  var $form;
  $form = $("#card-form");

  
/* Muestra los errores en la forma */
  html = "<ul  class=\"alert alert-danger\"><li>"+response.message+"</li></ul>";
  $form.find(".card-errors").append(html);
  $form.find("button").prop("disabled", false);
}; 

    $('#opPago1').change(function(){
    if($('#tarjetahabiente').prop("disabled") == false)
    {	     	         
    $("#tarjetahabiente").prop('disabled', true);
    $("#tarjeta").prop('disabled', true);
    $("#cvc").prop('disabled', true);
    $("#fecha_mes").prop('disabled', true);
    $("#fecha_anio").prop('disabled', true);
    }else
    {
    $("#tarjetahabiente").prop('disabled', false);	
    $("#tarjeta").prop('disabled', false);
    $("#cvc").prop('disabled', false);
    $("#fecha_mes").prop('disabled', false);
    $("#fecha_anio").prop('disabled', false);
    }   
  }); 
  
    $('#opPago2').change(function(){
    if($('#tarjetahabiente').prop("disabled") == false)
    {	     	         
    $("#tarjetahabiente").prop('disabled', true);
    $("#tarjeta").prop('disabled', true);
    $("#cvc").prop('disabled', true);
    $("#fecha_mes").prop('disabled', true);
    $("#fecha_anio").prop('disabled', true);
    }else
    {
    $("#tarjetahabiente").prop('disabled', false);
    $("#tarjeta").prop('disabled', false);
    $("#cvc").prop('disabled', false);
    $("#fecha_mes").prop('disabled', false);
    $("#fecha_anio").prop('disabled', false);	
    }   
  }); 

 $(document).ready(function(){
 	if(document.getElementById('opPago2').checked)
 	 {
    $("#tarjetahabiente").prop('disabled', false);
    $("#tarjeta").prop('disabled', false);
    $("#cvc").prop('disabled', false);
    $("#fecha_mes").prop('disabled', false);
    $("#fecha_anio").prop('disabled', false);	
    }
 	
   });	
</script>


@stop
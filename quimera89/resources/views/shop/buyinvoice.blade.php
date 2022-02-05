@extends('app')

@section('content')

<h3>Comprar Folios</h3>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Comprar</div>
				<div class="panel-body">

<form action="" method="POST" id="card-form">
<input type="hidden" name="_token" value="{{ csrf_token() }}">	
<h4>Folios</h4>
  
<div class="form-group">
<span>Número de folios</span><br />
<input class="form-control" id="cantidad" value="100" placeholder="25" type="text">
</div>

<div class="form-group">
<span>Subtotal</span><br />
<input class="form-control"  id="subtotal" value="100.00" type="text" readonly=true>
</div>

<div class="form-group">
<span>IVA</span><br />
<input class="form-control"  id="iva" type="text" value="16.00" readonly=true>
</div>

<div class="form-group">
<span>Total</span><br />
<input class="form-control"  id="total" name="total" value="116.00" type="text" readonly=true>
</div>

<hr />
<h4>Datos de pago</h4>
<span class="card-errors"></span>
   	
   <div class="form-group">
    
      <span>Nombre del tarjetahabiente</span><br />
      <input type="text" id="tarjetahabiente" size="20" data-conekta="card[name]" class="form-control" placeholder="Como en tu tarjeta" />
  
  </div>
  <div class="form-group">
    
      <span>Número de tarjeta de crédito</span><br />
      <input type="text" id="tarjeta" size="20" data-conekta="card[number]" class="form-control" placeholder="4242 4242 4242 4242" />
      
  </div>
  <div class="form-group">
    
      <span>CVC</span><br />
      <input type="text" id="cvc" size="4" data-conekta="card[cvc]" class="form-control"  placeholder="1234"/>
    
  </div>
  <div class="form-group">
    
      <span>Fecha de expiración (MM/AAAA)</span><br />
      <input type="text" id="fecha_mes" size="2" data-conekta="card[exp_month]" placeholder="MM" />
    <span>/</span>
    <input type="text" id="fecha_anio" size="4" data-conekta="card[exp_year]"  placeholder="AAAA" />
    

  </div>
  <button type="submit" class="btn btn-primary form-control" >Realizar cargo</button>
</form>

</div>
</div>
</div>
</div>
</div>
</div>

@stop

@section('js')
<script type="text/javascript" src="https://conektaapi.s3.amazonaws.com/v0.3.2/js/conekta.js"></script>

<script type="text/javascript">
 
 
  Conekta.setPublishableKey('key_KJysdbf6PotS2ut2');
 
 jQuery(function($) {
  $("#card-form").submit(function(event) {
    var $form;
    $form = $(this);
    
    
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


$('#cantidad').keyup(function(){     	         
   	  subtotal = parseFloat($('#cantidad').val() * 2);
   	  iva = subtotal * 0.16;
   	  total = subtotal + iva;
      $('#subtotal').val(subtotal.toFixed(2));
      $('#iva').val(iva.toFixed(2));
      $('#total').val(total.toFixed(2)); 
  }); 
  
 
</script>


@stop
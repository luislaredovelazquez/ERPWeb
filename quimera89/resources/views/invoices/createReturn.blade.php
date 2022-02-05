@extends('app')

@section('content')

<h3>Crea una nueva nota de crédito</h3>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Crear</div>
				<div class="panel-body">

@include('errors.list')

{!! Form::open(['url' => 'invoices/storeReturn']) !!}

@include('invoices.form',['SubmitButtonText' => 'Crear Nota de Crédito'])

{!! Form::close() !!}

</div>
</div>
</div>
</div>
</div>




@stop

@section('js')
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script type="text/javascript">

var importe = 0;
var descuento = 0;
var subtotal = 0;
var total = 0;

$(document).ready(function(){
	
	$('#c_cliente').select2();
	$('#c_concepto').select2();
	$('#impuestos').select2();
	
   $.ajax({
      url: 'create',
      type: "post",
      data: {'articulo':$('#c_concepto').val(), '_token': $('input[name=_token]').val()},
      success: function(data){
        $('#c_valorUnitario').val(data);
        $('#c_cantidad').val(1);
        var importe = $('#c_valorUnitario').val() * $('#c_cantidad').val();
        $('#c_importe').val(importe.toFixed(2));
      }
    }); 	
	
  $('#c_concepto').change(function(){     	         
    $.ajax({
      url: 'create',
      type: "post",
      data: {'articulo':$('#c_concepto').val(), '_token': $('input[name=_token]').val()},
      success: function(data){
        $('#c_valorUnitario').val(data);
        $('#c_cantidad').val(1);
        var importe = $('#c_valorUnitario').val() * $('#c_cantidad').val();
        $('#c_importe').val(importe.toFixed(2));
      }
    });      
  }); 
  
    $('#c_valorUnitario').keyup(function(){     	         
        var importe = $('#c_valorUnitario').val() * $('#c_cantidad').val();
        $('#c_importe').val(importe.toFixed(2));   
  }); 
  
    $('#c_cantidad').keyup(function(){     	         
        var importe = $('#c_valorUnitario').val() * $('#c_cantidad').val();
        $('#c_importe').val(importe.toFixed(2));   
  }); 
  
      $('#descuento').keyup(function(){     	         
   	  subtotal = importe - (parseFloat($('#descuento').val()/100 * importe));
      $('#subtotal').val(subtotal.toFixed(2)); 
  }); 
  
   $('#agregarConcepto').click(function(){  
   $('#tablaConceptos > tbody:last').append('<tr><td>'+$( "#c_concepto option:selected" ).text()+'</td><td>'+$('#c_valorUnitario').val()+'</td><td>'+$('#c_cantidad').val()+'</td><td>'+$('#c_importe').val()+'</td><td><button type=\"button\" class=\"btn btn-default quitar\" aria-label=\"Remove\">Quitar</button></td><input type=\"hidden\" name=\"item_concepto[]\" value=\"'+$('#c_concepto').val()+'@'+$('#c_valorUnitario').val()+'@'+$('#c_cantidad').val()+'\" /></tr>'); 
   
   importe = importe + parseFloat($('#c_importe').val());
   $('#importe').val(importe.toFixed(2));
   
   subtotal = importe - (parseFloat($('#descuento').val()/100 * importe));
   $('#subtotal').val(subtotal.toFixed(2));
   
   $('#collapse1').collapse('toggle');
   $('#collapse2').collapse('toggle');   
     
  }); 
  
  $(document).on('click', 'button.quitar', function () { 
  	
  	 var importe_concepto = $(this).closest('tr').children('td').eq(3).html();  	
  	 importe = importe - parseFloat(importe_concepto);
  	 $('#importe').val(importe.toFixed(2));
   
     subtotal = importe - (parseFloat($('#descuento').val()/100 * importe));
     $('#subtotal').val(subtotal.toFixed(2));
     $(this).closest('tr').remove();
     return false;
 });
 
 
   $('#agregarImpuesto').click(function(){  
   	
   var tasa = 0;	
   if(parseFloat($('#impuestos').val()) == 1)
   {
   tasa = 0.16;	
   }else if(parseFloat($('#impuestos').val()) == 2)
   {
   tasa = 0.00;	
   }else if(parseFloat($('#impuestos').val()) == 3)
   {
   tasa = 0.11;	
   }	
   	
   	
   var impuesto = subtotal * tasa;
   	   	         
   $('#tablaImpuestos > tbody:last').append('<tr><td>'+$( "#impuestos option:selected" ).text()+'</td><td>'+impuesto.toFixed(2)+'</td><td><button type=\"button\" class=\"btn btn-default quitarImpuesto\" aria-label=\"Remove\">Quitar</button></td><input type=\"hidden\" name=\"item_impuesto[]\" value=\"'+$('#impuestos').val()+'@'+impuesto.toFixed(2)+'\" /></tr>'); 
   
   
   total = subtotal + impuesto;
      
   $('#total').val(total.toFixed(2));
   
   $('#collapse3').collapse('toggle');
   $('#collapse4').collapse('toggle');   
     
  }); 
  
    $(document).on('click', 'button.quitarImpuesto', function () { 
  	
  	 var importe_impuesto = $(this).closest('tr').children('td').eq(1).html();  	
  	 total = total - parseFloat(importe_impuesto);
     $('#total').val(total.toFixed(2));
     $(this).closest('tr').remove();
     return false;
 });
 
  });
 
</script>

@stop
@extends('app')

@section('content')

<div class="row">
<div class="col-md-4"><h3>Folios disponibles: {{ $folios }}</h3></div>	
<div class="col-md-4  col-md-offset-4" style="text-align: right;"><h5><a style="cursor: pointer;" onclick="mostrarForma();">Comprar folios</a></h5></div>	
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
	{!! Form::open(['url' => 'shop/manageInvoice']) !!}
	<div class="form-group">
	{!! Form::label('pedido','Quiero comprar ') !!}
    {!! Form::select('pedido',['5' => '5 Folios','20' => '20 Folios','40' => '40 Folios',
    						   '60' => '60 Folios','80' => '80 Folios',
                               '100' => '100 Folios','200' => '200 Folios',
                               '1000' => '1000 Folios','10000' => '10000 Folios'],null,['class' => 'form-control','id' => 'pedido' ]) !!}
    </div>
    <label class="col-md-4 control-label">Subtotal: <span id="c_subtotal">99.00</span></label>
    <label class="col-md-4 control-label">Iva: <span id="c_iva">15.84</span></label>
    <label class="col-md-4 control-label">Total: <span><b  id="c_total">114.84</b></span></label>
    
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
                  <th>Receptor</th>
                  <th>Total Facturado</th>
                  <th>UUID</th>
                  <th>Fecha</th>
                  <th>Crear Addenda</th>
                  <th>Imprimir XML</th>
                  <th>Imprimir PDF</th>                  
                  <th>Cancelar</th>
                </tr>
              </thead>
              <tbody>
              	<?php $i=1; ?>
              	@foreach($resultados as $value)
                <tr>
                  <td>{{ $value->receptor }}</td>
                  <td>{{ $value->total_facturado }}</td>
                  <td>{{ $value->uuid }}</td>
                  <td>{{ $value->created_at }}</td>
                  @if($value->estado == 1)
                  <td>Addenda</td>
                  @elseif($value->estado_addenda == 1)
                  <td>Addenda</td>
                  @else
                  <td><a href="/invoices/{{ $value->id }}/addenda">Addenda</a></td>
                  @endif
                  @if($value->estado == 1)
                  <td><a href="/invoices/{{ $value->id }}/printCancelXML">XML</a></td>
                  <td><a href="/invoices/{{ $value->id }}/printCancelPDF">PDF</a></td>
                  <td><b>Cancelado</b></td>
                  @else
                  <td><a href="/invoices/{{ $value->id }}/printXML">XML</a></td>
                  <td><a href="/invoices/{{ $value->id }}/printPDF">PDF</a></td>
                  <td><a href="/invoices/{{ $value->uuid }}/cancel">Cancelar</a></td>
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
	
	 subtotal = $('#pedido').val() * 3;
      $('#c_subtotal').text(subtotal.toFixed(2));
       iva = subtotal * 0.16;
      $('#c_iva').text(iva.toFixed(2));
       total = subtotal + iva;
      $('#c_total').text(total.toFixed(2));     
	
  $('#pedido').change(function(){   
  	  subtotal = $('#pedido').val() * 3;
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
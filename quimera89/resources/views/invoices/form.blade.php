<h3>Cliente</h3>
<div class="form-group">	
{!! Form::label('c_cliente','Cliente') !!}
{!! Form::select('c_cliente',$clientes,
                          null,['class' => 'form-control']) !!}
</div>
<h3>Conceptos</h3>
<div class="panel-group" id="accordion">



  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        Agregar un concepto</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse">

<div class="form-group">	
{!! Form::label('c_concepto','Concepto') !!}
{!! Form::select('c_concepto',$articulos,
                          null,['class' => 'form-control','style' => 'width:100%;']) !!}
</div>

<!-- <div class="form-group">
{!! Form::label('c_unidad','Unidad') !!}
{!! Form::text('c_unidad','PZA',['class' => 'form-control','placeholder' => 'Unidad']) !!}
</div> -->

<div class="form-group">
{!! Form::label('c_valorUnitario','Valor Unitario') !!}
{!! Form::text('c_valorUnitario',null,['class' => 'form-control','placeholder' => 'Valor Unitario']) !!}
</div>

<div class="form-group">
{!! Form::label('c_cantidad','Cantidad') !!}
{!! Form::text('c_cantidad',1,['class' => 'form-control','placeholder' => 'Cantidad']) !!}
</div>

<div class="form-group">
{!! Form::label('c_importe','Importe') !!}
{!! Form::text('c_importe',null,['class' => 'form-control','placeholder' => 'Importe', 'readonly' => 'true']) !!}
</div>

<div class="form-group">
{!! Form::button("Agregar",['class' => 'btn btn-info form-control', 'id' => 'agregarConcepto' ]) !!}
</div>

    </div>
  </div>


 
<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
        Tus conceptos</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
<div class="table-responsive">
<table class="table table-hover" id="tablaConceptos">
   <thead>
                <tr>
                  <th>Concepto</th>
                  <th>Valor Unitario</th>
                  <th>Cantidad</th>
                  <th>Importe</th>
                  <th>Acción</th>
                </tr>
              </thead>
               <tbody>
               </tbody>
</table>
</div>
    </div>
  </div>




</div>

<div class="form-group">
{!! Form::label('importe','Importe') !!}
{!! Form::text('importe',"0.00",['class' => 'form-control','placeholder' => '0.00', 'readonly' => 'true']) !!}
</div>

<div class="form-group">
{!! Form::label('descuento','Descuento') !!}
{!! Form::text('descuento',"0.00",['class' => 'form-control','placeholder' => '0.00']) !!}
</div>


<div class="form-group">
{!! Form::label('subtotal','Subtotal') !!}
{!! Form::text('subtotal',"0.00",['class' => 'form-control','placeholder' => '0.00', 'readonly' => 'true']) !!}
</div>


<h3>Impuestos</h3>


<div class="panel-group" id="accordion2">



  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse3">
        Agregar impuestos</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">

<div class="form-group">	
{!! Form::label('impuestos','Impuestos') !!}
{!! Form::select('impuestos',['1' => 'Iva 16%','2' => 'Iva 0%','3' => 'IEPS 11%'],
                          null,['class' => 'form-control']) !!}
</div>

{!! Form::button("Agregar",['class' => 'btn btn-info form-control', 'id' => 'agregarImpuesto' ]) !!}

   </div>
  </div>


 
<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse4">
        Tus impuestos</a>
      </h4>
    </div>
    <div id="collapse4" class="panel-collapse collapse">
<div class="table-responsive">
<table class="table table-hover" id="tablaImpuestos">
   <thead>
                <tr>
                  <th>Impuesto</th>
                  <th>Importe</th>
                  <th>Acción</th>
                </tr>
              </thead>
               <tbody>
               </tbody>
</table>
</div>
    </div>
  </div>

</div>



<div class="form-group">
{!! Form::label('total','Total') !!}
{!! Form::text('total',"0.00",['class' => 'form-control','placeholder' => '0.00', 'readonly' => 'true']) !!}
</div>

<h3>Datos Generales</h3>
<div class="form-group">
{!! Form::label('metodoDePago','Método de Pago') !!}
{!! Form::select('metodoDePago',['1' => 'EFECTIVO','2' => 'TRANSFERENCIA ELECTRÓNICA'],
                          null,['class' => 'form-control']) !!}
</div>
<div class="form-group">
{!! Form::label('formaDePago','Forma de Pago') !!}
{!! Form::select('formaDePago',['1' => 'PAGO EN UNA SOLA EXHIBICION','2' => 'PAGO HECHO EN PARCIALIDADES'],
                          null,['class' => 'form-control']) !!}
</div>

<h3>Observaciones</h3>
<div class="form-group">
{!! Form::textarea('observaciones',null,['class' => 'form-control','size' => '50x5']) !!}
</div>



<div class="form-group">
{!! Form::submit($SubmitButtonText,['class' => 'btn btn-primary form-control']) !!}
</div>
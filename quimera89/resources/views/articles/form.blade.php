<div class="form-group">
{!! Form::label('codigo','Código') !!}
{!! Form::text('codigo',null,['class' => 'form-control','placeholder' => 'Escribe el código de tu producto o servicio']) !!}
</div>

<div class="form-group">
{!! Form::label('descripcion','Descripción') !!}
{!! Form::text('descripcion',null,['class' => 'form-control','placeholder' => 'Describe tu producto o servicio']) !!}
</div>

<div class="form-group">
{!! Form::label('precioVenta','Precio de Venta') !!}
{!! Form::text('precioVenta',null,['class' => 'form-control','placeholder' => 'Asígna un precio sin iva']) !!}
</div>

<div class="form-group">
{!! Form::label('unidad','Unidad') !!}
{!! Form::select('unidad',['PZA' => 'PIEZA','SERVICIO' => 'SERVICIO'],
                          null,['class' => 'form-control']) !!}
</div>

<div class="form-group">
{!! Form::submit($SubmitButtonText,['class' => 'btn btn-primary form-control']) !!}
</div>
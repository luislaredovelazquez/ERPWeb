<div class="form-group">
{!! Form::label('nombrecompleto','Nombre') !!}
{!! Form::text('nombrecompleto',null,['class' => 'form-control','placeholder' => '¿A quién le quieres facturar?']) !!}
</div>

<div class="form-group">
{!! Form::label('correo','Correo Electrónico') !!}
{!! Form::text('correo',null,['class' => 'form-control','placeholder' => '¿Tienes su correo electrónico?']) !!}
</div>

<div class="form-group">
{!! Form::label('telefono','Teléfono') !!}
{!! Form::text('telefono',null,['class' => 'form-control','placeholder' => 'Escribe su teléfono (10 dígitos)']) !!}
</div>

<div class="form-group">
{!! Form::label('rfc','RFC') !!}
{!! Form::text('rfc',null,['class' => 'form-control','placeholder' => 'Escribe su RFC']) !!}
</div>

<div class="form-group">
{!! Form::label('codigoPostal','Código Postal') !!}
{!! Form::text('codigoPostal',null,['class' => 'form-control','placeholder' => 'Escribe su código postal']) !!}
</div>

<div class="form-group">
{!! Form::label('pais','País') !!}
{!! Form::text('pais',null,['class' => 'form-control','placeholder' => 'Escribe su país']) !!}
</div>

<div class="form-group">
{!! Form::label('estado','Estado') !!}
{!! Form::text('estado',null,['class' => 'form-control','placeholder' => 'Escribe su estado']) !!}
</div>

<div class="form-group">
{!! Form::label('colonia','Colonia') !!}
{!! Form::text('colonia',null,['class' => 'form-control','placeholder' => 'Escribe su colonia']) !!}
</div>

<div class="form-group">
{!! Form::label('noInterior','Número Interior') !!}
{!! Form::text('noInterior',null,['class' => 'form-control','placeholder' => 'Escribe su número interior']) !!}
</div>

<div class="form-group">
{!! Form::label('noExterior','Número Exterior') !!}
{!! Form::text('noExterior',null,['class' => 'form-control','placeholder' => 'Escribe su número exterior']) !!}
</div>

<div class="form-group">
{!! Form::label('calle','Calle') !!}
{!! Form::text('calle',null,['class' => 'form-control','placeholder' => 'Escribe su calle']) !!}
</div>

<div class="form-group">
{!! Form::label('cuenta','Cuenta') !!}
{!! Form::text('cuenta',null,['class' => 'form-control','placeholder' => 'Escriba los 4 últimos dígitos de la cuenta de transferencias']) !!}
</div>

<div class="form-group">
{!! Form::submit($SubmitButtonText,['class' => 'btn btn-primary form-control']) !!}
</div>
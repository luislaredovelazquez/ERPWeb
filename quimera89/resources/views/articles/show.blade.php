@extends('app')

@section('content')


          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Código</th>
                  <th>Descripción</th>
                  <th>Precio Venta</th>
                </tr>
              </thead>
              <tbody>
              	<?php $i=1; ?>
              	@foreach($resultados as $value)
                <tr>
                  <td>{{ $i++ }}</td>
                  <td>{{ $value->codigo }}</td>
                  <td><a href="/articles/{{ $value->id }}/edit">{{ $value->descripcion }}</a></td>
                  <td>{{ $value -> precioVenta }}</td>
                 @endforeach 
                </tr>
              </tbody>
            </table>
          </div>

         <div class="row" style="text-align: center;">
         <?php echo $resultados->render(); ?>
         </div>

@endsection
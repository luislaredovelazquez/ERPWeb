@extends('app')

@section('content')


          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Receptor</th>
                  <th>RFC</th>
                  <th>Teléfono</th>
                </tr>
              </thead>
              <tbody>
              	<?php $i=1; ?>
              	@foreach($resultados as $value)
                <tr>
                  <td>{{ $i++ }}</td>
                  <td><a href="/clients/{{ $value->id }}/edit">{{ $value->nombrecompleto }}</a></td>
                  <td>{{ $value->rfc }}</td>
                  <td><a href="tel:{{ $value -> telefono }}">{{ $value -> telefono }}</a></td>
                 @endforeach 
                </tr>
              </tbody>
            </table>
          </div>

         <div class="row" style="text-align: center;">
         <?php echo $resultados->render(); ?>
         </div>

@endsection
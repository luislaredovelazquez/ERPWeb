@extends('app')

@section('content')


          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Descripción</th>
                  <th>Cantidad</th>
                  <th>Total</th>
                  <th>Fecha</th>
                  <th>Status</th>
                  <th>XML</th>
                  <th>PDF</th>
                  <th>Facturar</th>
                </tr>
              </thead>
              <tbody>
              	<?php $i=1; ?>
              	@foreach($resultados as $value)
                <tr>
                  <td>{{ $value->descripcion }}</td>
                  <td>{{ $value->cantidad }}</td>
                  <td>{{ $value -> total }}</td>
                  <td>{{ $value -> updated_at }}</td>
                  <td>{{ $value -> status }}</td>
                  @if($value -> status == 'Pendiente')
                  <td>XML</td>
                  <td>PDF</td>
                  <td>Facturar</td>	
                  @elseif($value -> status == 'Facturada')
                  <td><a href="/shop/{{ $value->id }}/printxml">XML</a></td>
                  <td><a href="/shop/{{ $value->id }}/printpdf">PDF</a></td>
                  <td>Facturar</td>                  	
                  @else
                  <td>XML</td>
                  <td>PDF</td>
                  <td><a href="/shop/{{ $value->id }}/invoice">Facturar</a></td> 
                  @endif

                 @endforeach 
                </tr>
              </tbody>
            </table>
          </div>

         <div class="row" style="text-align: center;">
         <?php echo $resultados->render(); ?>
         </div>

@endsection
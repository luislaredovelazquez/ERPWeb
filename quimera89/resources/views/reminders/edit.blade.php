@extends('app')

@section('content')

<h2>Aviso</h2>

<h3><a href="/clients/{{ $resultados->idClie }}/edit">{{ $resultados -> nombrecompleto }}</a> </h3>


@if( $resultados->tipo  == '1')
<h4>
Motivo: {{ $resultados -> motivo }}
</h4>
<h4>
Monto: {{ $resultados -> monto }}
</h4>	
@elseif( $resultados->tipo  == '3')
<audio controls src="{{ $audio }}" style="width: 250px;"></audio>
@elseif( $resultados->tipo  == '4')
<h4>
Texto: 
</h4>	
{{ $resultados -> recurso }}
@endif  

<hr class="featurette-divider">

        
          <div class="table-responsive">
          <table class="table table-hover">
              <thead>
                <tr>
                  <th>Tipo</th>
                  <th>Lapso</th>	
                  <th>Fecha de Inicio</th>
                  <th>Próximo Aviso</th>
                  <th>Fecha Final</th>
                   
                </tr>
              </thead>
              <tbody>
              	<?php $i=1; ?>
                <tr>
                @if( $resultados->tipo  == '1')	
                  <td>Cobro</td>
                @elseif( $resultados->tipo  == '2')
                  <td>Cumpleaños</td>
                @elseif( $resultados->tipo  == '3')
                  <td>Voz</td>
                @elseif( $resultados->tipo  == '4')
                  <td>Abierto</td>   
                    
                @endif  
                  
                  @if( $resultados->lapso  == '1')	
                  <td>Una sola vez</td>
                  @elseif( $resultados->lapso  == '2')
                  <td>Cada semana</td>
                  @elseif( $resultados->lapso  == '3')
                  <td>Cada quincena</td>
                  @elseif( $resultados->lapso  == '4')
                  <td>Cada mes</td>
                  @elseif( $resultados->lapso  == '5')
                  <td>Cada bimestre</td>
                  @elseif( $resultados->lapso  == '6')
                  <td>Cada semestre</td>  
                  @elseif( $resultados->lapso  == '7')
                  <td>Cada año</td>  
                  @endif
                  
                  
                  <td>{{ $resultados->fechainicio }}</td>
                  <td>{{ $resultados->fechaactual }}</td>
                  <td>{{ $resultados->fechafinal }}</td>
                  
                                   
                </tr>
              </tbody>
            </table>
           </div>
	
@stop
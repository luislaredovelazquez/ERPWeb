@extends('app')

@section('content')
<script src="{{ asset('assets/js/Chart.js') }}"></script>
<link href="{{ asset('assets/css/justified-nav.css') }}" rel="stylesheet">



      <!-- The justified navigation menu is meant for single line per list item.
           Multiple lines will require custom code not provided by Bootstrap. -->
      <div class="masthead">
        <h3 class="text-muted">Pizarra</h3>
      <nav>
          <ul class="nav nav-justified">
            <!--<li class="active"><a href="#">Alta Clientes</a></li> --> 
            <li><a href="invoices/create">Crear Factura</a></li>
            <li><a href="reminders/show">Consultar Avisos</a></li>
            <li><a href="clients/create">Alta Cliente</a></li>
          </ul>
        </nav> 
      </div>

 <hr class="featurette-divider">

<div class="row" style="text-align: center;">

@if($facturas_vigentes == 0 && $facturas_canceladas == 0 && $montos_vigentes == 0 && $montos_cancelados == 0 && $publico_general == 0 && $clientes == 0)
    <div class="col-md-8 col-md-offset-2 alert alert-success" role="alert">
    	<h2>¡Bienvenido!</h2>Aún no has realizado ninguna actividad para poder generar reportes.  
    </div>
@else    
	<div class="col-md-4">
		<canvas id="operaciones"></canvas>
		<h4>Facturas</h2>
	</div>
	<div class="col-md-4">
		<canvas id="montos"></canvas>
		<h4>Clientes</h2>
	</div>
	<div class="col-md-4">
		<canvas id="operaciones_terminadas"></canvas>
		<h4>Montos</h2>
	</div>
 @endif	
		
</div>


@if(count($invoice_items) > 0)
    <hr class="featurette-divider">
          
          <h2 class="sub-header">Tus últimas facturas</h2>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Cuenta</th>
                  <th>Monto</th>
                  <th>Fecha</th>
                </tr>
              </thead>
              <tbody>
               <?php $i=1; ?>
              	@foreach($invoice_items as $item)
                <tr>
                  <td>{{ $i++ }}</td>
                  @if($item -> id == '1')
                  <td>{{ $item -> nombrecompleto }}</td>
                  @else
                  <td><a href="clients/{{ $item -> id }}/edit">{{ $item -> nombrecompleto }}</a></td>
                  @endif
                  <td>{{ $item -> total_facturado }}</td>
                  <td>{{ $item -> created_at }}</td>
                 @endforeach 
              </tbody>
            </table>
          </div>
@endif
@if(count($cancel_items) > 0)
 <hr class="featurette-divider">

        <h2 class="sub-header">Tus últimos avisos</h2>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Cuenta</th>
                  <th>Tipo</th>
                  <th>Fecha</th>
                </tr>
              </thead>
              <tbody>
               <?php $i=1; ?>
              	@foreach($cancel_items as $item)
                <tr>
                  <td>{{ $i++ }}</td>
                  @if($item -> id == '1')
                  <td>{{ $item -> nombrecompleto }}</td>
                  @else
                  <td><a href="clients/{{ $item -> id }}/edit">{{ $item -> nombrecompleto }}</a></td>
                  @endif
                  @if($item -> tipo ==  1)
                  <td>Cobro</td>
                  @elseif($item -> tipo == 2)
                  <td>Cumpleaños</td>
                  @elseif($item -> tipo == 3)
                  <td>Voz</td>
                  @elseif($item -> tipo == 4)
                  <td>Libre</td>
                  @endif
                  <td>{{ $item -> created_at }}</td>
                 @endforeach 
              </tbody>
            </table>
          </div>
@endif
@if($facturas_vigentes > 0 || $facturas_canceladas > 0 || $montos_vigentes > 0 || $montos_cancelados > 0 || $publico_general > 0 || $clientes > 0)
	<script>
          var opData = [
				{
					value: {{ $facturas_canceladas }},
					color: "#204d74",
					highlight: "#337ab7",
					label: "Facturas Canceladas"
				},
				{
					value: {{ $facturas_vigentes }},
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "Facturas Vigentes"
				}

			];
	
	Chart.types.Bar.extend({
    
    name: "BarOneTip",
    initialize: function(data){
        Chart.types.Bar.prototype.initialize.apply(this, arguments);
    },
    getBarsAtEvent : function(e){
			var barsArray = [],
				eventPosition = Chart.helpers.getRelativePosition(e),
				datasetIterator = function(dataset){
					barsArray.push(dataset.bars[barIndex]);
				},
				barIndex;

			for (var datasetIndex = 0; datasetIndex < this.datasets.length; datasetIndex++) {
				for (barIndex = 0; barIndex < this.datasets[datasetIndex].bars.length; barIndex++) {
					if (this.datasets[datasetIndex].bars[barIndex].inRange(eventPosition.x,eventPosition.y)){
                        
                        //change here to only return the intrested bar not the group
						barsArray.push(this.datasets[datasetIndex].bars[barIndex]);
						return barsArray;
					}
				}
			}

			return barsArray;
		},
    showTooltip : function(ChartElements, forceRedraw){
        console.log(ChartElements);
			// Only redraw the chart if we've actually changed what we're hovering on.
			if (typeof this.activeElements === 'undefined') this.activeElements = [];

			var isChanged = (function(Elements){
				var changed = false;

				if (Elements.length !== this.activeElements.length){
					changed = true;
					return changed;
				}

				Chart.helpers.each(Elements, function(element, index){
					if (element !== this.activeElements[index]){
						changed = true;
					}
				}, this);
				return changed;
			}).call(this, ChartElements);

			if (!isChanged && !forceRedraw){
				return;
			}
			else{
				this.activeElements = ChartElements;
			}
			this.draw();
            console.log(this)
			if (ChartElements.length > 0){
                
                //removed the check for multiple bars at the index now just want one
					Chart.helpers.each(ChartElements, function(Element) {
						var tooltipPosition = Element.tooltipPosition();
						new Chart.Tooltip({
							x: Math.round(tooltipPosition.x),
							y: Math.round(tooltipPosition.y),
							xPadding: this.options.tooltipXPadding,
							yPadding: this.options.tooltipYPadding,
							fillColor: this.options.tooltipFillColor,
							textColor: this.options.tooltipFontColor,
							fontFamily: this.options.tooltipFontFamily,
							fontStyle: this.options.tooltipFontStyle,
							fontSize: this.options.tooltipFontSize,
							caretHeight: this.options.tooltipCaretSize,
							cornerRadius: this.options.tooltipCornerRadius,
							text: Chart.helpers.template(this.options.tooltipTemplate, Element),
							chart: this.chart
						}).draw();
					}, this);
				
			}
			return this;
		}
	
});
    
	
	
			
	var barChartData = {
		labels : ["Clientes"],
		datasets : [
			{
				label: "Público en General",
				fillColor : "rgba(220,220,220,0.5)",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : [{{ $publico_general }}]
			},
			{
				label: "Clientes Registrados",
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : [{{ $clientes }}]
			}
		]

	}

           	var opfData = [
				{
					value: {{ $montos_vigentes }},
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "Montos Vigentes"
				},
				{
					value: {{ $montos_cancelados }},
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "Montos Cancelados"
				}

			];

			window.onload = function(){
		        var ctx = document.getElementById("operaciones").getContext("2d");
				window.myDoughnut = new Chart(ctx).Doughnut(opData,{ responsive: true });
				
		        var ctx = document.getElementById("montos").getContext("2d");
		        window.myBar = new Chart(ctx).BarOneTip(barChartData, {responsive : true, showTooltip: true,tooltipTemplate: "<%= datasetLabel %>: <%= value %>" });
		        
		        var ctx = document.getElementById("operaciones_terminadas").getContext("2d");
				window.myPie = new Chart(ctx).Pie(opfData,{ responsive: true });
			};



	</script>
@endif
@stop
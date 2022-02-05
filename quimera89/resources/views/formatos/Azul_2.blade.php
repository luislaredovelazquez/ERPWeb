<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>	
    <style>
      body{ font-size:15px;  background-image:url(./assets/images/Azul_2.jpg); background-repeat:repeat; }
    </style>
</head>
<body>
<div class="container">
<div style="width: 100%; background-color:white;">
<img src="{{ $logo }}" style="height: 40px; width:auto; " />
<br/><br/>
</div>

<table style="width: 700px;">
<tr>	
<td>	
<div style="width: 400px;  font-size: 12px;">	
<h4>	
{{ 	$nombre_emisor }}<br />
{{ 	$rfc_emisor }}<br />
{{	$regimenFiscal }}
</h4>
<h4> Dirección </h4>
Calle {{	$calle_emisor }}<br /> 
Número Exterior {{ $noExterior_emisor }}<br />
Número Interior {{	$noInterior_emisor }}<br />
Colonia {{	$colonia_emisor }}<br />
Localidad {{ $localidad_emisor }}<br />
Municipio {{	$municipio_emisor }}<br />
Estado {{	$estado_emisor }}<br />
País {{	$pais_emisor }} <br />
Código Postal{{	$codigoPostal_emisor }}<br />
</div>
</td>
<td>
<div style="width: 300px;   font-size: 12px;">
<h4>Comprobante</h4>	
Folio {{ 	$folio 	 }}<br />
Tipo de Comprobante {{ 	$tipoDeComprobante 	 }}<br />	
Fecha {{	$fecha 	 }}<br />
Fecha de Timbrado {{	$FechaTimbrado }}<br />
Número de Certificado {{	$noCertificado 	 }}<br />
Número de Certificado del SAT {{	$noCertificadoSAT }}<br />
Lugar de Expedición {{ 	$LugarExpedicion  }}<br />
Forma de Pago {{	$formaDePago 	 }}<br />
Método de Pago {{ 	$metodoDePago 	 }}<br />
<!-- Moneda {{ 	$Moneda 	 }}<br /> -->
UUID {{	$uuid   }}<br />
</div>
</td>
</tr>
<tr>	
<td>
<div style="width: 400px;   font-size: 12px;">
<h4>Cliente</h4>
{{	$nombre_receptor }}<br />
{{	$rfc_receptor }}<br />
Número de Cuenta: {{	$NumCtaPago }}
<br /><br />
Calle {{ $calle_receptor }}<br />
Número Exterior {{	$noExterior_receptor }}<br />
Colonia {{	$colonia_receptor }}<br />
Estado {{	$estado_receptor }}<br />
País {{	$pais_receptor }}  Código Postal {{	$codigoPostal_receptor }}<br />	<br />
</div>	
</td>

<td style="width: 300px; text-align: right;">

<div>
<img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(135)->generate($cadenaReporte)) }} " />	
</div>
	
</td>	

</tr>	
<tr>

<table style="width: 100%; font-size: 12px;">
<thead>
<tr>
     <th>Código</th>
     <th>Descripción</th>
     <th>Unidad</th>
     <th>Valor Unitario</th>
     <th>Cantidad</th>
     <th>Pieza</th>
</tr>	
</thead>	
	
@foreach ($conceptos as $concepto)
<tr>
<td> {{ $concepto['noIdentificacion'] }} </td>
<td> {{  $concepto['descripcion']  }}</td>	
<td> {{ $concepto['unidad']  }}</td>
<td> {{ $concepto['valorUnitario']  }}</td>
<td> {{ $concepto['cantidad'] }} </td>
<td> {{ $concepto['importe']  }}</td>
</tr>    	   
@endforeach		
	
	
</table>	
	
</tr>
</table>




<!-- <div>
<h4> Expedido en: </h4>
Calle {{	$calle_ExpedidoEn }}<br />
Número Exterior {{	$noExterior_ExpedidoEn }}<br />
Número Interior{{	$noInterior_ExpedidoEn }}<br />
Colonia {{	$colonia_ExpedidoEn }}<br />
Localidad {{	$localidad_ExpedidoEn }}<br />
Municipio {{	$municipio_ExpedidoEn }}<br />
Estado {{	$estado_ExpedidoEn }}<br />
País {{	$pais_ExpedidoEn }}<br />
Código Postal {{	$codigoPostal_ExpedidoEn }}<br />	
</div> -->



<table style="width: 700px; font-size: 12px;">
<tr style="width: 700px;">
<td style="text-align: right;">

<h4>Total</h4>	
Descuento {{	$descuento  }}<br /> 
Subtotal {{	$subTotal 	 }}<br /> 
@foreach ($traslados as $traslado)
{{ $traslado['impuesto'].":".$traslado['importe'] }}<br /> 
@endforeach
Total {{ 	$total 	 }}	<br /> 	
	
</td>	

</tr>	
</table>

<div style="width: 700px; word-break: break-all;  word-wrap: break-word; font-size: 10px;">	
<b>Cadena Original</b><br /> {{ 	$cadena_original }}<br /> 
<b>Sello</b><br /> {{ 	$sello }} <br />
<b>Sello SAT</b><br /> {{	$selloSAT }}<br />
<b>Observaciones</b><br /> {{ 	$observaciones }}<br />
</div> 	
<div style="text-align: center; font-size: 12px;">
<b>Este documento es una representación impresa de un CFDI</b>		
</div>


</div>
</body>
</html>
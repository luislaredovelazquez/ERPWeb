<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>	
</head>
<body>
<div style="width: 100%; text-align: right;">	
<img src="assets/images/logo.jpg">
</div>
<div style="width: 100%; text-align: center;">
<h3>
Código de compra para OXXO	
</h3>	
<img src="assets/images/oxxo.jpg">
</div>

<div>
<h3 style="text-align: center;">
Instrucciones	
</h3>	
<ul>
  <li>Imprime y presenta este comprobante en cualquier tienda OXXO del país para realizar el pago de tu compra.</li>
  <li>Pide al cajero que escanee el código de barras y realiza el pago que el mismo te indique. Guarda tu recibo de pago por si es
  	  necesario en caso de cualquier aclaración.</li>
  <li>La acreditación de tu pago se hará en 24 horas y será automático</li>
  <li>La tienda en donde se realice el pago cobrará $8.00 pesos por concepto de recepción de cobranza</li>
</ul>	
	
</div>

<div>
<ul>	
<li><b>Fecha de Vencimiento:</b> {{ 	$expires_at 	 }}</li>	
<li><b>Valor:</b> {{ 	 $amount 	 }}</li>
<li><b>Referencia:</b> {{ 	 $referencia 	 }}</li>	
</ul>
</div>
	
<div class="row">	
<table>
<tr>
</tr>	
</table>		
</div>
<div class="container" style="width: 100%; text-align: center;">
<img src="{{ 	$url_bc 	 }}">
<br />
{{ 	$barcode }}
<br />
<br />
</div>
</body>
</html>
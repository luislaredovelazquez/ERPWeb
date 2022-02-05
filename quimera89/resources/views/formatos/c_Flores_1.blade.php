<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>	
<style>
      body{ font-size:15px;  background-image:url(./assets/images/ba_Flores_1.jpg); background-repeat:repeat; }
</style>
</head>
<body>
<div class="row" style="text-align: center;">	
<img src="assets/images/b_Flores_1.jpg">
</div>	
<div style="width: 100%; text-align: center;"><h3>Acuse de Cancelación CFDI</h3></div>	
<div class="container">
<table style="width: 700px;">
<tr>	
<td>	
<h4>Fecha y hora de solicitud:</h4>	
{{ 	$Hora }}<br />
<h4>Fecha y hora de cancelación:</h4>	
{{ 	$Hora }}<br />
<h4>RFC Emisor:</h4>	
{{ 	$Emisor }}<br /><br /><br />
</td>
</tr>
<tr>
<table style="width: 100%; border: 1px solid green;">
<thead>
<tr>
     <th>Folio Fiscal</th>
     <th>Estado CFDI</th>
</tr>	
</thead>	
	
<tr style="text-align: center;">
<td> {{ $uuid }} </td>
<td> Cancelado </td>	
</tr>    	   
	
	
	
</table>	
	
</tr>
</table>

<br />
<br />
<br />


<div style="width: 700px; word-break: break-all;  word-wrap: break-word;">	
<h4>Sello Digital SAT:</h4> {{ $SignatureValue }} <br />
</div>
 	
</div>
</body>
</html>
<?php
include 'basedat.php';
require("t/Services/Twilio.php"); 


        $hoy = date("Y-m-d");
		$manana = date("Y-m-d",strtotime($hoy . ' + 1 day'));
		$mensaje = "";
		$hora = date("H");
		$hora = $hora + 1;

		$result = mysqli_query($conexion,"SELECT r.recurso as recurso,r.id as id, r.idusuario as idusuario, r.idcliente as idcliente, u.name as name,c.telefono as telefono,r.lapso as lapso,r.fechafinal as fechafinal,r.motivo as motivo,r.monto as monto, r.tipo as tipo,r.hora as hora, r.sms as sms, c.correo as correo    
        FROM clientes c, recordatorio r, usuarios u WHERE r.fechaactual = '".$hoy."' AND r.hora = '".$hora."' AND r.status = 0 AND r.idusuario = u.id AND r.idcliente = c.id;");
		
		if($result === FALSE) { 
		mysqli_close($conexion);
		exit;
		}
      
        while($row = mysqli_fetch_array($result,MYSQLI_BOTH)){
        	
			
		$validacion = mysqli_query($conexion,"SELECT recordatorios FROM usuarios WHERE id = '".$row['idusuario']."';");	
		$numrecordatorios = 0;
		
		
		while($row2 = mysqli_fetch_array($validacion,MYSQLI_BOTH)){
		
		$numrecordatorios = $row2['recordatorios']; 
		
		if($row2['recordatorios'] == 0 && $row['sms'] == 0)
		return;	
		
		
		}	
			
        	
		$mensajeMovil = '';
					
	    if($row['tipo'] == 1) //Cobro
	    {
	    $mensajeMovil = 'Recuerda realizar el pago de '.$row['monto'].' a '.$row['name'].' por concepto de '.$row['motivo'].'. quimerasystems.com';	
	    }elseif($row['tipo'] == 2) //Cumpleaños
	    {
	    $mensajeMovil = $row['name'].' desea que este día este lleno de sorpresas para tí, Feliz Cumpleaños!. quimerasystems.com';	
	    }elseif($row['tipo'] == 3) //Cumpleaños
	    {
	    $mensajeMovil = 'Has recibido un recordatorio de voz de '.$row['name'].' Consultalo en quimerasystems.com/voice/'.$row['recurso'];	
	    }elseif($row['tipo'] == 4) //Cumpleaños
	    {
	    $mensajeMovil = $row['recurso'];	
	    }
	    
	    
		/* $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
    	); */
 
      $mensajeMovil =  utf8_encode($mensajeMovil);
      // $mensajeMovil = mb_convert_encoding($mensajeMovil, 'ISO-8859-15', 'UTF-8');
       $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
    );
    $mensajeMovil = preg_replace(array_keys($utf8), array_values($utf8), $mensajeMovil);
		

		$nuevaFecha = '';
		
		if($row['lapso'] == 1)
		{
		$nuevaFecha = $row['fechafinal'];	//Una vez
		}elseif($row['lapso'] == 2)
		{
		$nuevaFecha = date("Y-m-d",strtotime($hoy . ' + 7 day'));	//Cada semana
		}elseif($row['lapso'] == 3)
		{
		$nuevaFecha = date("Y-m-d",strtotime($hoy . ' + 14 day'));	//Cada Quince días
		}elseif($row['lapso'] == 4)
		{
		$nuevaFecha = date("Y-m-d",strtotime($hoy . ' + 1 month'));	//Cada mes
		}elseif($row['lapso'] == 5)
		{
		$nuevaFecha = date("Y-m-d",strtotime($hoy . ' + 2 month'));	//Cada 2 meses
		}elseif($row['lapso'] == 6)
		{
		$nuevaFecha = date("Y-m-d",strtotime($hoy . ' + 6 month'));	//Cada semestre
		}elseif($row['lapso'] == 7)
		{
		$nuevaFecha = date("Y-m-d",strtotime($hoy . ' + 1 year'));   //Cada año	
		}


if($row['sms'] == 0)
{
	
	$account_sid = 'ACb16dfc84712f3d2267086740065a5df9'; 
    $auth_token = '56aa0f96b755746b1339db2d195aa260'; 
	
	 try {
    $client = new Services_Twilio($account_sid, $auth_token); 
    $client->account->messages->create(array(  
    'To' => "+52".$row['telefono'], 
	'From' => "+525549998797",    
	'Body' => $mensajeMovil,  
      )); 
		 } catch (Services_Twilio_RestException $e) {
    
}

	$numrecordatorios = $numrecordatorios - 1;	
	$consulta = "UPDATE  usuarios  SET recordatorios = '".$numrecordatorios."' 
    WHERE id = '".$row['idusuario']."';";
    $result = mysqli_query($conexion,$consulta) or die("Error en consulta");	

		 
}else
{
	
	     $subject = $row['name'].' te ha enviado un aviso por quimerasystems.com';

         $headers = "From: contacto@quimerasystems.com\r\n"; 
         $headers .= "MIME-Version: 1.0\r\n";
         $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

         $message = '<html><head></head><body>';
         $message .= $mensajeMovil;
         $message .= "</body></html>";
		 

         mail($row['correo'], $subject, $message, $headers);
		
}	      
	
		$final_dt = new DateTime($row['fechafinal']);
		$nuevo_dt = new DateTime($nuevaFecha);

		if ($nuevo_dt < $final_dt) {
		
		//Actualizar fechaactual
		    $consulta = "UPDATE  recordatorio  SET fechaactual = '".$nuevaFecha."' 
    		WHERE id = '".$row['id']."';";
	
    		$result = mysqli_query($conexion,$consulta) or die("Error en consulta");
		
			
		}else
		{
		
		//Poner status 2 de completado o expirado	
		    $consulta = "UPDATE  recordatorio  SET status = '2' 
    		WHERE id = '".$row['id']."';";
	
    		$result = mysqli_query($conexion,$consulta) or die("Error en consulta");			
				
		}
	

	}
			
	mysqli_close($conexion);	

?>
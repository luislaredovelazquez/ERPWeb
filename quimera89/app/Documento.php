<?php namespace App;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\InvoiceRequest;
use App\CFDI;
use App\InformacionFiscal;
use App\ArchivosFiscales;
use App\Clientes;
use App\usuarios_sistema;

use DOMDocument;
use XSLTProcessor;
use Auth;
use SoapClient;

class Documento  {
	
	    function crearDocumento(InvoiceRequest $request,$addenda="") {
        global $numfolio, $certificado,$total_facturado,$rfc_emisor,$rfc_receptor,$observaciones;
        $arr = array();
		$observaciones = $request->get('observaciones');
		//Encabezados Generales
		$usuario = usuarios_sistema::where('id','=',Auth::id())->first();
		
		$folio = CFDI::where('idUsuario','=',Auth::id())->orderBy('id', 'desc')->first();
		if($folio == null)
		$numfolio = 0;
		else
		$numfolio = $folio -> folio;
		$numfolio = $numfolio+1;
		
		
		$arr['folio'] = $numfolio;
        $arr['fecha'] = date("Y-m-d")."T".date("H:i:s");
		$arr['version'] = "3.2";
		$arr['subTotal'] = number_format($request->get('subtotal'),2,'.', '');
		$arr['total'] = number_format($request->get('total'),2,'.', '');
		$total_facturado = $arr['total']; 
		
		$metodoDePago = "";
		switch ($request->get('metodoDePago')) {
			case '1':
		$metodoDePago = "EFECTIVO";		
			break;
				
			case '2':
		$metodoDePago = "TRANSFERENCIA ELECTRÓNICA";		
			break;	
			
			default:
		$metodoDePago = "EFECTIVO";		
			break;
		}
		
		$arr['metodoDePago'] = $metodoDePago;
		$arr['tipoDeComprobante'] = "ingreso";
		
		$formaDePago = "";
		switch ($request->get('formaDePago')) {
			case '1':
		$formaDePago = "PAGO EN UNA SOLA EXHIBICION";		
			break;
				
			case '2':
		$formaDePago = "PAGO HECHO EN PARCIALIDADES";		
			break;	
			
			default:
		$formaDePago = "PAGO EN UNA SOLA EXHIBICION";		
			break;
		}
		
        $arr['formaDePago'] = $formaDePago;
		$arr['condicionesDePago']="NO APLICA";
        $arr['TipoCambio']="1.0";
        $arr['Moneda']="MXN";
        
		
		//Emisor
		
		$info_fiscal = InformacionFiscal::where('idUsuario','=',Auth::id())->first();
		
		$arr['Emisor']['nombre'] = $usuario ->name;
        $arr['Emisor']['rfc'] = $info_fiscal -> rfc;
		$rfc_emisor = $info_fiscal -> rfc;
        $arr['Emisor']['Regimen'] = $info_fiscal -> regimen;
        
		
		$noCertificado = ArchivosFiscales::where('idusuario','=',Auth::id())->first();
		$arr['noCertificado'] = $noCertificado -> num_certificado;
		
		
		$arr['Emisor']['calle'] = $info_fiscal -> calle;
        $arr['Emisor']['noExterior'] = $info_fiscal -> noExterior;
        $arr['Emisor']['noInterior'] = $info_fiscal -> noInterior;
		$arr['Emisor']['colonia'] = $info_fiscal -> colonia;
        $arr['Emisor']['localidad'] = $info_fiscal -> localidad;
        $arr['Emisor']['municipio'] = $info_fiscal -> municipio;
        $arr['Emisor']['estado'] = $info_fiscal -> estadoRepublica;
        $arr['Emisor']['pais'] = $info_fiscal -> pais;
        $arr['Emisor']['codigoPostal'] = $info_fiscal -> codigoPostal;
		
        $arr['Emisor']['ExpedidoEn']['calle'] = $info_fiscal -> e_calle;
        $arr['Emisor']['ExpedidoEn']['noExterior'] = $info_fiscal -> e_noExterior;
        $arr['Emisor']['ExpedidoEn']['noInterior'] = $info_fiscal -> e_noInterior;
		$arr['Emisor']['ExpedidoEn']['colonia'] = $info_fiscal -> e_colonia;
        $arr['Emisor']['ExpedidoEn']['localidad'] = $info_fiscal -> e_localidad;
        $arr['Emisor']['ExpedidoEn']['municipio'] = $info_fiscal -> e_municipio;
        $arr['Emisor']['ExpedidoEn']['estado'] = $info_fiscal -> e_estado;
        $arr['Emisor']['ExpedidoEn']['pais'] = $info_fiscal -> e_pais;
        $arr['Emisor']['ExpedidoEn']['codigoPostal'] = $info_fiscal -> e_codigoPostal;
		$arr['LugarExpedicion']=$info_fiscal -> e_municipio;
		
		
		//Receptor
		
		$receptor = Clientes::where('id','=',$request->get('c_cliente'))->first();
		
		$arr['Receptor']['nombre'] = $receptor ->nombrecompleto;
		$arr['Receptor']['rfc'] = $receptor ->rfc;
		$rfc_receptor = $receptor ->rfc;
		$arr['NumCtaPago'] = $receptor ->cuenta;
		
		$arr['Receptor']['Domicilio']['calle'] = $receptor ->calle;
		$arr['Receptor']['Domicilio']['noExterior'] = $receptor ->noExterior;
		$arr['Receptor']['Domicilio']['noInterior'] = $receptor ->noInterior;
		$arr['Receptor']['Domicilio']['colonia'] = $receptor ->colonia;
		$arr['Receptor']['Domicilio']['estado'] = $receptor ->estado;
        $arr['Receptor']['Domicilio']['pais'] = $receptor ->pais;
        $arr['Receptor']['Domicilio']['codigoPostal'] = $receptor ->codigoPostal;
        
		//Partidas
		
		
        $i=0;
		$importeTotal = 0;
		foreach($request->get('item_concepto') as $item_concepto)
		{
			
		$datos = explode("@",$item_concepto);
		$id = $datos[0];
		$valorUnitario = $datos[1];
		$cantidad = $datos[2];  	
		$importe = $cantidad * $valorUnitario;
		$importeTotal = $importeTotal +  $importe;
			
		$articulo = Articulos::where('id','=',$id)->first();
		
		$arr['Conceptos'][$i]['descripcion'] = $articulo -> descripcion;
		$arr['Conceptos'][$i]['cantidad'] = $cantidad;
        $arr['Conceptos'][$i]['unidad']=$articulo -> unidad;
        $arr['Conceptos'][$i]['noIdentificacion']=$articulo -> codigo;
		$arr['Conceptos'][$i]['valorUnitario'] =  $valorUnitario;
        $arr['Conceptos'][$i]['importe'] = number_format($importe,2,'.', ''); 
		
		$i++;	
		}
		
		//Impuestos
		
		$descuentoTotal = $importeTotal * ($request->get('descuento') / 100 );
		
	    $arr['descuento'] = number_format($descuentoTotal,2,'.', '');
		
		
		$i=0;
		$totalImpuestosTrasladados = 0.00;
		foreach($request->get('item_impuesto') as $item_impuesto)
		{
			
	    $datos = explode("@",$item_impuesto);
		$id = $datos[0];
		$importe = $datos[1];
		  	
		     if($id == 1)
		{
		$arr['Traslado'][$i]['impuesto'] = "IVA";
  		$arr['Traslado'][$i]['tasa'] = "16.00";			
		}elseif($id == 2)
		{
		$arr['Traslado'][$i]['impuesto'] = "IVA";
  		$arr['Traslado'][$i]['tasa'] = "0.00";			
		}elseif($id == 3)
		{
		$arr['Traslado'][$i]['impuesto'] = "IEPS";
  		$arr['Traslado'][$i]['tasa'] = "11.00";			
		}			
 
		$arr['Traslado'][$i]['importe'] = number_format($importe,2,'.', '');
		$totalImpuestosTrasladados  = $totalImpuestosTrasladados + $importe;
		$i++;
		}
		$arr['Traslados']['totalImpuestosTrasladados'] = number_format($totalImpuestosTrasladados,2,'.', '');
		
		
		$arr['addenda'] = $request->get('addenda');
		
		return $arr;
		
          }

function termina() {
global $xml,$numfolio,$total_facturado,$rfc_emisor,$rfc_receptor,$cadena_original,$observaciones;
$xml->formatOutput = true;
$xml->encoding = 'utf-8';
$xml_sinSello = $xml->saveXML();

//Guardar el xml sin sello en un archivo
/*
$dir = "storage/app/".Auth::id().'/';                    //Para ponerlo en el servidor 
$dir = "../storage/app/".Auth::id().'/';                 //Para ponerlo local
$nombre_factura = "LAVL8707071G0_sinSello".$numfolio;    // Junta el numero de factura   serie + folio
$file=$dir.$nombre_factura.".xml";
$xml->save($file);
*/
 
 //sellar
 /*
 try{
 //Generamos el cliente soap
  $client = new SoapClient("https://timbrado.rfacil.com:8443/timbrado_masivo/InterconectaWs?wsdl" ,array(
       'trace' => true,
       'login' => '',
       'password' => '',
       'cache_wsdl' => WSDL_CACHE_NONE,
  ));
}
catch (Exception $e){
  echo ( '<pre>' );
  print_r('Fallo la conexión: ');
  print_r( '<br/>' );
  print_r( (isset($e)? $e : 'No error found' ) );
  echo ( '</pre>' );
}
 
try {
     //Consumimos un metodo del webservice y almacenamos la respuesta en una variable
     $res = $client->TimbraEnviaCFDIxp(
          array(
                'user' => 'LAVL8707071G0_92',
                'userPassword' => '859399365762907214437821',
                'certificadoEmisor' => null,
                'llavePrivadaEmisor' => null,
                'llavePrivadaEmisorPassword' => '',
                'xmlCFDI' => $xml->saveXML(),
                'versionCFDI' => '3.2'
          )
     );
} catch ( Exception $e ) {
     print_r('Failed WS: code - ' . $e->getCode() . '- [' . $e->getFile() . ']line: ' . $e->getLine() . ', '  . $e->getMessage() );
}
*/

//Imprimimos el resultado, cabe mencionar que el resultado es un objeto de donde se pueden recuperar los datos de la respuesta
/*
echo ( '<pre>' );
print_r( (isset($res)? $res : 'No value found' ) );//Imprime el objeto completo
print_r( (isset($res->return->folioUUID)? $res->return->folioUUID : 'No value found' ) );//Imprime solamente una propiedad de la respuesta
echo ( '</pre>' ); 

Storage::disk('local')->put(Auth::id().'/'."CFDI_LAVL8707071G0".$numfolio.".xml",  $res->return->XML); 
 * 
 */ 
$input = [];
$input['idusuario'] = Auth::id();
$input['folio'] = $numfolio;
$input['emisor'] = $rfc_emisor;
$input['receptor'] = $rfc_receptor;
$input['total_facturado'] = $total_facturado;
$input['cadena_original'] = $cadena_original;
$input['observaciones'] = $observaciones;
// $input['uuid'] = $res->return->folioUUID; //Cuando se selle cambiar por este
$input['uuid'] = "ad662d33-6934-459c-a128-BDf0393f0f44";
// $input['factura'] = $res->return->XML; //Cuando se selle cambiar por este
$input['factura'] = $xml_sinSello;
$input['addenda'] = '';
$input['estado_addenda'] = 0;
$input['estado'] = 0;
$input['cancelacion'] = '';
$cfdi = CFDI::create($input);

 
return $cfdi->id;
}

function sella() {
	
global $root, $cadena_original, $sello;

$noCertificado = ArchivosFiscales::where('idusuario','=',Auth::id())->first();

$ruta = "";
$crypttext="";
$file=$noCertificado -> llave; // Ruta al archivo

// Obtiene la llave privada del Certificado de Sello Digital (CSD),
//    Ojo , Nunca es la FIEL/FEA
$pkeyid = openssl_get_privatekey(Storage::disk('local')->get(Auth::id().'/'.$file.'.pem'));
openssl_sign($cadena_original, $crypttext, $pkeyid, OPENSSL_ALGO_SHA1);
openssl_free_key($pkeyid);
 
$sello = base64_encode($crypttext);      // lo codifica en formato base64
$root->setAttribute("sello",$sello);
 
$certificado = $noCertificado -> num_certificado;      // Ruta al archivo de Llave publica
$file = $certificado.".cer";
$datos = Storage::disk('local')->get(Auth::id().'/'.$file.'.pem');
$datos = preg_replace("/-----BEGIN CERTIFICATE-----/", "", $datos);
$datos = preg_replace("/-----END CERTIFICATE-----/", "", $datos);
$datos = preg_replace("/\n/", "", $datos);
$datos = preg_replace("/\r/", "", $datos);
$certificado = $datos; 
/*$carga=false;
for ($i=0; $i<sizeof($datos); $i++) {
    if (strstr($datos[$i],"END CERTIFICATE")) $carga=false;
    if ($carga) $certificado .= trim($datos[$i]);
    if (strstr($datos[$i],"BEGIN CERTIFICATE")) $carga=true;
}
// El certificado como base64 lo agrega al XML para simplificar la validacion */
$root->setAttribute("certificado",$certificado);
}

function genera_cadena_original() {
global $xml, $cadena_original;
$paso = new DOMDocument("1.0","UTF-8");
$paso->loadXML($xml->saveXML());
$xsl = new DOMDocument("1.0","UTF-8");
$ruta = "../storage/app/";
//$ruta = "storage/app/";
$file=$ruta."cadenaoriginal_3_2.xslt";      // Ruta al archivo
$xsl->load($file);
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); 
$cadena_original = $proc->transformToXML($paso);
$cadena_original = $str = str_replace("\n", '', $cadena_original);
}


function cargaAtt(&$nodo, $attr) {
global $xml, $sello;
$quitar = array('sello'=>1,'noCertificado'=>1,'certificado'=>1);
foreach ($attr as $key => $val) {
   
    $val = preg_replace('/\s\s+/', ' ', $val);   // Regla 5a y 5c
    $val = trim($val);                           // Regla 5b
    if (strlen($val)>0) {   // Regla 6
        $val = str_replace(array('"','>','<'),"'",$val);  
        $val = str_replace("|","/",$val); // Regla 1
        $nodo->setAttribute($key,$val);
    } 
}
}

function impuestos($arr) {
global $root, $xml;
$impuestos = $xml->createElement("cfdi:Impuestos");
$impuestos = $root->appendChild($impuestos);

    $traslados = $xml->createElement("cfdi:Traslados");
    $traslados = $impuestos->appendChild($traslados);
	for ($i=0; $i<sizeof($arr['Traslado']); $i++) {
	$traslado = $xml->createElement("cfdi:Traslado");
    $traslado = $traslados->appendChild($traslado);	
    $this->cargaAtt($traslado, array("impuesto"=>$arr['Traslado'][$i]['impuesto'],
                              "tasa"=>$arr['Traslado'][$i]['tasa'],
                              "importe"=>$arr['Traslado'][$i]['importe']
                             )
                         );
	}

$impuestos->SetAttribute("totalImpuestosTrasladados",$arr['Traslados']['totalImpuestosTrasladados']);
}


function conceptos($arr) { 
global $root, $xml;
$conceptos = $xml->createElement("cfdi:Conceptos");
$conceptos = $root->appendChild($conceptos);
 for ($i=0; $i<sizeof($arr['Conceptos']); $i++) {
    $concepto = $xml->createElement("cfdi:Concepto");
    $concepto = $conceptos->appendChild($concepto);
    $this->cargaAtt($concepto, array("cantidad"=>$arr['Conceptos'][$i]['cantidad'],
                              "unidad"=>$arr['Conceptos'][$i]['unidad'],
                              "descripcion"=>$arr['Conceptos'][$i]['descripcion'],
                              "valorUnitario"=>$arr['Conceptos'][$i]['valorUnitario'],
                              "noIdentificacion"=>$arr['Conceptos'][$i]['noIdentificacion'],
                              "importe"=>$arr['Conceptos'][$i]['importe'],
                   )
                );

}
}

function receptor($arr) { 
global $root, $xml;
$receptor = $xml->createElement("cfdi:Receptor");
$receptor = $root->appendChild($receptor);
$this->cargaAtt($receptor, array("rfc"=>$arr['Receptor']['rfc'],
                          "nombre"=>$arr['Receptor']['nombre']
                      )
                  );
$domicilio = $xml->createElement("cfdi:Domicilio");
$domicilio = $receptor->appendChild($domicilio);
$this->cargaAtt($domicilio, array("calle"=>$arr['Receptor']['Domicilio']['calle'],
                        "noExterior"=>$arr['Receptor']['Domicilio']['noExterior'],
                       "colonia"=>$arr['Receptor']['Domicilio']['colonia'],
                       "estado"=>$arr['Receptor']['Domicilio']['estado'],
                       "pais"=>$arr['Receptor']['Domicilio']['pais'],
                       "codigoPostal"=>$arr['Receptor']['Domicilio']['codigoPostal'],
                   )
               );

}

function emisor($arr) { //Esto después deberá cambiar para tener un arreglo
global $root, $xml;
$emisor = $xml->createElement("cfdi:Emisor");
$emisor = $root->appendChild($emisor);
$this->cargaAtt($emisor, array("rfc"=>$arr['Emisor']['rfc'],
                       "nombre"=>$arr['Emisor']['nombre']
                   )
                );
$domfis = $xml->createElement("cfdi:DomicilioFiscal");
$domfis = $emisor->appendChild($domfis);
$this->cargaAtt($domfis, array("calle"=>$arr['Emisor']['calle'],
                        "noExterior"=>$arr['Emisor']['noExterior'],
                        "noInterior"=>$arr['Emisor']['noInterior'],
                        "colonia"=>$arr['Emisor']['colonia'],
                        "localidad"=>$arr['Emisor']['localidad'],
                        "municipio"=>$arr['Emisor']['municipio'],
                        "estado"=>$arr['Emisor']['estado'],
                        "pais"=>$arr['Emisor']['pais'],
                        "codigoPostal"=>$arr['Emisor']['codigoPostal']
                   )
                );
				
$expedidoEn = $xml->createElement("cfdi:ExpedidoEn");
$expedidoEn = $emisor->appendChild($expedidoEn);
$this->cargaAtt($expedidoEn, array("calle"=>$arr['Emisor']['ExpedidoEn']['calle'],
                        "noExterior"=>$arr['Emisor']['ExpedidoEn']['noExterior'],
                        "noInterior"=>$arr['Emisor']['ExpedidoEn']['noInterior'],
                        "colonia"=>$arr['Emisor']['ExpedidoEn']['colonia'],
                        "localidad"=>$arr['Emisor']['ExpedidoEn']['localidad'],
                        "municipio"=>$arr['Emisor']['ExpedidoEn']['municipio'],
                        "estado"=>$arr['Emisor']['ExpedidoEn']['estado'],
                        "pais"=>$arr['Emisor']['ExpedidoEn']['pais'],
                        "codigoPostal"=>$arr['Emisor']['ExpedidoEn']['codigoPostal']
                   )
                );				
				
$regimen = $xml->createElement("cfdi:RegimenFiscal");
$expedido = $emisor->appendChild($regimen);
$this->cargaAtt($regimen, array("Regimen"=>$arr['Emisor']['Regimen']
                   )
                );
}

		function generales($arr) {
		global $root, $xml;
		$root = $xml->createElement("cfdi:Comprobante");
		$root = $xml->appendChild($root);
					 
		$root->setAttribute("xmlns:cfdi","http://www.sat.gob.mx/cfd/3");
		$root->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
		$root->setAttribute("xsi:schemaLocation","http://www.sat.gob.mx/cfd/3  http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd");
					 
        $this->cargaAtt($root, array("version"=>"3.2",
                      "folio"=>$arr['folio'],
                      "fecha"=>$arr['fecha'],
                      "sello"=>"@",
                      "formaDePago"=>$arr['formaDePago'],
                      "noCertificado"=>$arr['noCertificado'],
                      "certificado"=>"@",
                      "subTotal"=>$arr['subTotal'],
                      "descuento"=>$arr['descuento'],
                      "Moneda"=>$arr['Moneda'],
                      "total"=>$arr['total'],
                      "tipoDeComprobante"=>$arr['tipoDeComprobante'],
                      "metodoDePago"=>$arr['metodoDePago'],
                      "LugarExpedicion"=>$arr['LugarExpedicion'],
                      "NumCtaPago"=>$arr['NumCtaPago']
                   )
                );
         }



		function genera_xml(array $arr) {
			global $xml, $ret;
			$xml = new DOMdocument("1.0","UTF-8");
			$this->generales($arr);
			$this->emisor($arr);
			$this->receptor($arr);
			$this->conceptos($arr);
			$this->impuestos($arr);
			//Las addendas no pasan las validaciones... Hay que checar si pasa las validaciones con los PAC's
			$ok = $this->valida();
            if (!$ok) {
            $this->display_xml_errors(); //Hacer algo si not ok
            echo "Error al validar XSD\n";
            }  
			
			}
	   
	   function crearFactura(array $arr){
	   	
		$this->genera_xml($arr);
        $this->genera_cadena_original();
        $this->sella();
        $cadena_reporte = $this->termina();
		
		return $cadena_reporte;
		
	   }
	   
	   function valida() {
 		global $xml, $texto;
		$xml->formatOutput=true;
		$paso = new DOMDocument("1.0","UTF-8");
		$texto = $xml->saveXML();
		$paso->loadXML($texto);
		libxml_use_internal_errors(true);
		libxml_clear_errors();
		
    	$ok = $paso->schemaValidate("http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd");
		return $ok;
		}
	   
	   function display_xml_errors() {
       global $texto;
    		$lineas = explode("\n", $texto);
    		$errors = libxml_get_errors();
 
    		foreach ($errors as $error) {
        	echo $this->display_xml_error($error, $lineas);
    	}
 
	    $this->libxml_clear_errors();
}
	   
	   function display_xml_error($error, $lineas) {
    $return  = $lineas[$error->line - 1]. "\n";
    $return .= str_repeat('-', $error->column) . "^\n";
 
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "Warning $error->code: ";
            break;
                                                       
         case LIBXML_ERR_ERROR:
            $return .= "Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "Fatal Error $error->code: ";
            break;
    }
 
    $return .= trim($error->message) .
               "\n  Linea: $error->line" .
               "\n  Columna: $error->column";
    echo "$return\n\n--------------------------------------------\n\n";
}

}

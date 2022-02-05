<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\CFDI;
use App\InformacionFiscal;
use XMLSecurityKey;
use XMLSecurityDSig;

use DOMDocument;
use XSLTProcessor;
use Auth;
use SoapClient;


class Cancelacion_Documento  {



	function cancelaDocumento($folio){
	global $xml, $root;
	
	   $info_fiscal = InformacionFiscal::where('idUsuario','=',Auth::id())->first();
	   $archivos = ArchivosFiscales::where('idusuario','=',Auth::id())->first();
	/*
		$xml = new DOMdocument("1.0","UTF-8");
		$root = $xml->createElement("Cancelacion");
		$root = $xml->appendChild($root);
				
		$root->setAttribute("xmlns","http://cancelacfd.sat.gob.mx");
		$root->setAttribute("xmlns:xsd","http://www.w3.org/2001/XMLSchema");
		$root->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");

		
		
		$this->cargaAtt($root, array("Fecha"=>date('Y-m-d\TH:i:s'),
                      				 "RfcEmisor"=>$info_fiscal -> rfc)
                );
         $this->cargaAtt($root, array("Fecha"=>"2015-06-04T15:30:21",
                      				 "RfcEmisor"=>$info_fiscal -> rfc));        
                
				
		$folios = $xml->createElement("Folios");
				
				
		$uuids = $xml->createElement("UUID");
		$uuid = $xml->createTextNode($folio);
		$uuids->appendChild($uuid);
		$folios->appendChild($uuids);
		$root->appendChild($folios); */
		
		$fecha = date('Y-m-d\TH:i:s');
		$emisor = $info_fiscal -> rfc;
		$uuid = $folio;
		
		$xmlBase = '<Cancelacion xmlns="http://cancelacfd.sat.gob.mx" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Fecha="'.$fecha.'" RfcEmisor="'.$emisor.'"><Folios><UUID>'.$uuid.'</UUID></Folios></Cancelacion>';		

		
		/*$getToken_XMLSecurityDSig = new XMLSecurityDSig(); 
		$getToken_XMLSecurityDSig -> setCanonicalMethod(XMLSecurityDSig::C14N); 
		
		$options=[];
		$options['prefix'] = '';
		$options['prefix_ns'] = '';
		$options['force_uri'] = TRUE;
		$options['id_name'] = 'ID';
		

		$getToken_XMLSecurityDSig -> addReference($xml, XMLSecurityDSig::SHA1, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'), $options);
		
		$XMLSecurityKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private')); 
        $XMLSecurityKey -> loadKey(storage_path()."/app/".Auth::id().'/'.$archivos->llave.'.pem', TRUE); 
		$getToken_XMLSecurityDSig -> sign($XMLSecurityKey); 
		
		
		$opciones = [];
		$opciones['issuerSerial'] = TRUE;
		$getToken_XMLSecurityDSig -> add509Cert(file_get_contents(storage_path()."/app/".Auth::id().'/'.$archivos->num_certificado.'.cer.pem'),TRUE,False,$opciones);
		$getToken_XMLSecurityDSig -> appendSignature($xml -> documentElement); 
		$mensaje1 = $xml->saveXML();*/
		
		$xml = new DOMDocument( "1.0","UTF-8" );
        $xml->loadXML($xmlBase);
		//echo $xml->C14N();
		
		//método 2
		$canonicalized = $xml->C14N(); 
		$digest = base64_encode(pack("H*", sha1($canonicalized)));  
		
		$firmaBase = '<SignedInfo xmlns="http://www.w3.org/2000/09/xmldsig#" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"></CanonicalizationMethod><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"></SignatureMethod><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"></Transform></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></DigestMethod><DigestValue>'.$digest.'</DigestValue></Reference></SignedInfo>';
		
		$crypttext = "";
		$pkeyid = openssl_get_privatekey(Storage::disk('local')->get(Auth::id().'/'.$archivos->llave.'.pem'));
		openssl_sign($firmaBase, $crypttext, $pkeyid, OPENSSL_ALGO_SHA1);
		openssl_free_key($pkeyid);
		 
		$sello = base64_encode($crypttext);      // lo codifica en formato base64
		$datos = Storage::disk('local')->get(Auth::id().'/'.$archivos->certificado.'.pem');
		// $datos = Storage::disk('local')->get(Auth::id().'/emisor.cer.pem');
		// $certif = $datos;
		$certificadoInfo = openssl_x509_parse ($datos);
		$parts = array();
        foreach ($certificadoInfo['issuer'] AS $key => $value) {
        array_unshift($parts, "$key=$value");
        }
        $issuerName = implode(',', $parts);
		$serialNumber = $certificadoInfo['serialNumber'];
		
		$datos = preg_replace("/-----BEGIN CERTIFICATE-----/", "", $datos);
		$datos = preg_replace("/-----END CERTIFICATE-----/", "", $datos);
		$datos = preg_replace("/\n/", "", $datos);
		$datos = preg_replace("/\r/", "", $datos);
		$certificado = $datos; 
		
		$xmlFinal = '<?xml version="1.0" encoding="UTF-8"?><Cancelacion xmlns="http://cancelacfd.sat.gob.mx" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Fecha="'.$fecha.'" RfcEmisor="'.$emisor.'"><Folios><UUID>'.$uuid.'</UUID></Folios><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" /><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" /><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" /></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" /><DigestValue>'.$digest.'</DigestValue></Reference></SignedInfo><SignatureValue>'.$sello.'</SignatureValue><KeyInfo><X509Data><X509IssuerSerial><X509IssuerName>'.$issuerName.'</X509IssuerName><X509SerialNumber>'.$serialNumber.'</X509SerialNumber></X509IssuerSerial><X509Certificate>'.$certificado.'</X509Certificate></X509Data></KeyInfo></Signature></Cancelacion>';
		
		
		
		
		//$firma = '<SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"></CanonicalizationMethod><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"></SignatureMethod><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"></Transform></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></DigestMethod><DigestValue>'.$digest.'</DigestValue></Reference></SignedInfo>';	
		

		
		
		
		
		

		/* $penultima = '<Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"></CanonicalizationMethod><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"></SignatureMethod><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"></Transform></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></DigestMethod><DigestValue>'.$digest.'</DigestValue></Reference></SignedInfo><SignatureValue>'.$sello.'</SignatureValue><KeyInfo><X509Data><X509IssuerSerial><X509IssuerName>'.$issuerName.'</X509IssuerName><X509SerialNumber>'.$serialNumber.'</X509SerialNumber></X509IssuerSerial><X509Certificate>'.$certificado.'</X509Certificate></X509Data></KeyInfo></Signature>';
		$firmaXML = $xml -> createDocumentFragment();
		$firmaXML -> appendXML($penultima);
		$root->appendChild($firmaXML); */
		


		/*
		$ok = openssl_verify($firmaBase, base64_decode($sello), $certif, OPENSSL_ALGO_SHA1);
		if ($ok == 1) {
    	echo "valid";
		} elseif ($ok == 0) {
    	echo "invalid";
		} else {
    	echo "error: ".openssl_error_string();
		}   */
		
		// $xml->formatOutput = true;
		// $xml->encoding = 'utf-8';
		
		//Aquí va lo del webservice
		
		//sellar
		 // return $xml->saveXML();
		 // return $xmlFinal;
 
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
     $res = $client->cancelaCFDIxp(
          array(
                'user' => 'LAVL8707071G0_92',
                'userPassword' => '859399365762907214437821',
                'xmlPeticionCancelacionSellada' => $xmlFinal
          )
     );
	 
	 
// return $res;//->return->ErrorMessage." ".$res->return->IsError." ".$res->return->numError;	 
	 
} catch ( Exception $e ) {
     return 'Failed WS: code - ' . $e->getCode() . '- [' . $e->getFile() . ']line: ' . $e->getLine() . ', '  . $e->getMessage();
	 
}
//Aquí va lo del webservice

				
		$cfdi = CFDI::where('uuid', '=', $folio)->firstOrFail();
		$cfdi->estado = 1;
	    $cfdi->cancelacion = $res->return->acuse; //Cuando se selle cambiar por este
		// $cfdi->cancelacion = $xml->saveXML();
		$cfdi->save();
				
		return;
		
	}
	
	
	function cargaAtt(&$nodo, $attr) {
	global $xml;
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





}

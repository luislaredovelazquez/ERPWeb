<?php namespace App;

use Illuminate\Support\Facades\Storage;
use Auth;
use DB;
use PDF;
use SimpleXMLElement;
use DOMDocument;

class Reportes  {
	
function crearPDF($factura)
{
		 /* Crear pdf */
		 
		$data = [];
		$xmlstr = $factura -> factura ; 
		$xml = new SimpleXMLElement($xmlstr);
		
        $data['sello'] =  (string)$xml['sello'];
		$data['folio'] =  (string)$xml['folio'];
		$data['fecha'] =  (string)$xml['fecha'];
		$data['formaDePago'] =  (string)$xml['formaDePago'];
		$data['noCertificado'] =  (string)$xml['noCertificado'];
		$data['NumCtaPago'] =  (string)$xml['NumCtaPago'];
		$data['subTotal'] =  (string)$xml['subTotal'];
		$data['descuento'] =  (string)$xml['descuento'];
		$data['Moneda'] =  (string)$xml['Moneda'];
		$data['total'] =  (string)$xml['total'];
		$data['tipoDeComprobante'] =  (string)$xml['tipoDeComprobante'];
		$data['metodoDePago'] =  (string)$xml['metodoDePago'];
		$data['LugarExpedicion'] =  (string)$xml['LugarExpedicion'];
		$data['observaciones'] =  $factura -> observaciones;
		$data['cadena_original'] =  $factura -> cadena_original;
		
		
		$namespaces = $xml->getNameSpaces(true);
		$hijos = $xml->children($namespaces['cfdi']);
      
		$data['rfc_emisor'] = (string)$hijos->Emisor->attributes()['rfc'];
		$data['nombre_emisor'] = (string)$hijos->Emisor->attributes()['nombre'];
	  
		$data['calle_emisor'] = (string)$hijos->Emisor->DomicilioFiscal->attributes()['calle'];
		$data['noExterior_emisor'] = (string)$hijos->Emisor->DomicilioFiscal->attributes()['noExterior'];
		$data['noInterior_emisor'] = (string)$hijos->Emisor->DomicilioFiscal->attributes()['noInterior'];
		$data['colonia_emisor'] = (string)$hijos->Emisor->DomicilioFiscal->attributes()['colonia'];
		$data['localidad_emisor'] = (string)$hijos->Emisor->DomicilioFiscal->attributes()['localidad'];
		$data['municipio_emisor'] = (string)$hijos->Emisor->DomicilioFiscal->attributes()['municipio'];
		$data['estado_emisor'] = (string)$hijos->Emisor->DomicilioFiscal->attributes()['estado'];
		$data['pais_emisor'] = (string)$hijos->Emisor->DomicilioFiscal->attributes()['pais'];
		$data['codigoPostal_emisor'] = (string)$hijos->Emisor->DomicilioFiscal->attributes()['codigoPostal'];
	    
	   	
	   	$data['calle_ExpedidoEn'] = (string)$hijos->Emisor->ExpedidoEn->attributes()['calle'];
		$data['noExterior_ExpedidoEn'] = (string)$hijos->Emisor->ExpedidoEn->attributes()['noExterior'];
		$data['noInterior_ExpedidoEn'] = (string)$hijos->Emisor->ExpedidoEn->attributes()['noInterior'];
		$data['colonia_ExpedidoEn'] = (string)$hijos->Emisor->ExpedidoEn->attributes()['colonia'];
		$data['localidad_ExpedidoEn'] = (string)$hijos->Emisor->ExpedidoEn->attributes()['localidad'];
		$data['municipio_ExpedidoEn'] = (string)$hijos->Emisor->ExpedidoEn->attributes()['municipio'];
		$data['estado_ExpedidoEn'] = (string)$hijos->Emisor->ExpedidoEn->attributes()['estado'];
		$data['pais_ExpedidoEn'] = (string)$hijos->Emisor->ExpedidoEn->attributes()['pais'];
		$data['codigoPostal_ExpedidoEn'] = (string)$hijos->Emisor->ExpedidoEn->attributes()['codigoPostal'];
	   
	   $data['regimenFiscal'] = (string)$hijos->Emisor->RegimenFiscal->attributes()['Regimen'];
	   
	   
	   	$data['rfc_receptor'] = (string)$hijos->Receptor->attributes()['rfc'];
		$data['nombre_receptor'] = (string)$hijos->Receptor->attributes()['nombre'];
	  
		$data['calle_receptor'] = (string)$hijos->Receptor->Domicilio->attributes()['calle'];
		$data['noExterior_receptor'] = (string)$hijos->Receptor->Domicilio->attributes()['noExterior'];
		$data['colonia_receptor'] = (string)$hijos->Receptor->Domicilio->attributes()['colonia'];
		$data['estado_receptor'] = (string)$hijos->Receptor->Domicilio->attributes()['estado'];
		$data['pais_receptor'] = (string)$hijos->Receptor->Domicilio->attributes()['pais'];
		$data['codigoPostal_receptor'] = (string)$hijos->Receptor->Domicilio->attributes()['codigoPostal'];
	   	
		$p_conceptos = [];
		foreach ($hijos->Conceptos->Concepto as $concepto) {
			
		$partidas = [];			
		$partidas['cantidad'] = (string)$concepto->attributes()['cantidad'];
		$partidas['unidad'] = (string)$concepto->attributes()['unidad'];
		$partidas['descripcion'] = (string)$concepto->attributes()['descripcion'];
		$partidas['valorUnitario'] = (string)$concepto->attributes()['valorUnitario'];
		$partidas['noIdentificacion'] = (string)$concepto->attributes()['noIdentificacion'];
		$partidas['importe'] = (string)$concepto->attributes()['importe'];
		$p_conceptos[] = $partidas;	
		}
		
		$data['conceptos'] = $p_conceptos;    
		
	    $p_traslados = [];
		foreach ($hijos->Impuestos->Traslados->Traslado as $concepto) {
			
		$partidas = [];			
		$partidas['impuesto'] = (string)$concepto->attributes()['impuesto'];
		$partidas['importe'] = (string)$concepto->attributes()['importe'];
		$p_traslados[] = $partidas;	
		}
				
		$data['traslados'] = $p_traslados;    
		
		
		$total_facturado = number_format($factura -> total_facturado,6,'.', '');
        $total_facturado = str_pad($total_facturado,17,"0",STR_PAD_LEFT);
        $cadena_reporte = "?re=".$factura -> emisor."&rr=".$factura -> receptor."&tt=".$total_facturado."&id=".$factura -> uuid;
		
		$pac = $hijos->Complemento->children($namespaces['tfd']);		
		$data['uuid'] = (string)$pac->TimbreFiscalDigital->attributes()['UUID'];
		$data['noCertificadoSAT'] = (string)$pac->TimbreFiscalDigital->attributes()['noCertificadoSAT'];
		$data['FechaTimbrado'] = (string)$pac->TimbreFiscalDigital->attributes()['FechaTimbrado'];
		$data['selloSAT'] = (string)$pac->TimbreFiscalDigital->attributes()['selloSAT']; 
		
	    /* $data['uuid'] = "123456";
		$data['noCertificadoSAT'] = "67890";
		$data['FechaTimbrado'] = "25-08-2015";
		$data['selloSAT'] = "skqauw91728191"; */
		
		$input = ArchivosFiscales::where('idusuario', '=', Auth::id())->first();
		$data['logo'] = storage_path().'/app/'.Auth::id().'/'.$input->logo;
		
		
		$data['cadenaReporte'] = $cadena_reporte;
		
		$formato = DB::table('usuarios')
        ->join('formatos_cfdi', function($join)
        {
            $join->on('usuarios.formato', '=', 'formatos_cfdi.id')
                 ->where('usuarios.id', '=', Auth::id());
        })
		->select('formatos_cfdi.nombre_Formato as nombre')
        ->first();
		
		$nombre_formato = strtolower($formato-> nombre); 
		// $nombre_formato = 'standard';
		
		$pdf = PDF::loadView('formatos.'.$nombre_formato,$data);
        //return $pdf->download('invoice.pdf');		 
		return $pdf;
		 /* Crear pdf */	
}	
	
	
	
}
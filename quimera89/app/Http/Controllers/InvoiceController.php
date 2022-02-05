<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Http\Requests\FormatRequest;
use Illuminate\Support\Facades\Storage;
use Response;

use Auth;
use DB;
use DOMDocument;
use Mail;
use Services_Twilio;
use PDF;

use App\Documento;
use App\DocumentoNota;
use App\Cancelacion_Documento;
use App\Clientes;
use App\Articulos;
use App\CFDI;
use App\FormatosCFDI;
use App\usuarios_sistema;
use App\Addenda;
use App\Reportes;
use App\Suscripcion;

//use Illuminate\Http\Request;
use Request;

class InvoiceController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	 
	public function __construct()
	{
		$this->middleware('auth');
	}
	 
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
	  if(Request::ajax()) {		  	  
	  $articulos = Articulos::where('id', '=', Request::get('articulo'))->first();		  
      print_r($articulos -> precioVenta);die;
      return;
      }else
	   {
	  $data = [];
		
		$clientes = Clientes::where('id', '=', '1')
		->orWhere('idusuario', '=', Auth::id())
		->lists('nombrecompleto','id');
				
		$data['clientes'] = $clientes;
		
		$articulos = Articulos::where('idusuario', '=', Auth::id())
		->lists('descripcion','id');
		
		$data['articulos'] = $articulos;		
		return view('invoices.create',$data);
	   }
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function createReturn()
	{
		
	  if(Request::ajax()) {		  	  
	  $articulos = Articulos::where('id', '=', Request::get('articulo'))->first();		  
      print_r($articulos -> precioVenta);die;
      return;
      }else
	   {
	  $data = [];
		
		$clientes = Clientes::where('id', '=', '1')
		->orWhere('idusuario', '=', Auth::id())
		->lists('nombrecompleto','id');
				
		$data['clientes'] = $clientes;
		
		$articulos = Articulos::where('idusuario', '=', Auth::id())
		->lists('descripcion','id');
		
		$data['articulos'] = $articulos;		
		return view('invoices.createReturn',$data);
	   }
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(InvoiceRequest $request)
	{
		
	$banderaFactura = false;
	
	if(Auth::user()->suscripcion > 0)
	{
		$suscripcion = Suscripcion::where('idusuario', '=', Auth::user()->id)->where('tipo','=','1')->where('status','=','0')->first();
		if($suscripcion->itemsConsumidos<$suscripcion->itemsAsignados)
		{
		$banderaFactura = true;	
		}else
		{
			if(Auth::user()->folios > 0)
			{
			$banderaFactura = true;
			}else
			{
			$banderaFactura = false;	
			}	
		}
				
	}else
	{
			if(Auth::user()->folios > 0)
			{
			$banderaFactura = true;
			}else 
			{
			$banderaFactura = false;	
			}		
	}		
		
		
		
	if($banderaFactura == false)
	{
	return redirect()->back()->withErrors(['Lo sentimos no cuentas con folios para realizar esta factura']);	
	}	
	
	
	
	
	   $factura = new Documento;
       $arregloDocumento = $factura->crearDocumento($request);
	   $id = $factura->crearFactura($arregloDocumento);
	   
	   
	  if(Auth::user()->suscripcion > 0)
	{
		$suscripcion = Suscripcion::where('idusuario', '=', Auth::user()->id)->where('tipo','=','1')->where('status','=','0')->first();
		if($suscripcion->itemsConsumidos<$suscripcion->itemsAsignados)
		{
			    $suscripcionItems = [];
				$nuevosFolios = $suscripcion->itemsConsumidos;
				$nuevosFolios = $nuevosFolios + 1;
				$input = Suscripcion::findOrFail($suscripcion->id);
				$suscripcionItems['itemsConsumidos'] = $nuevosFolios;
				$input -> update($suscripcionItems);
		}else
		{
			if(Auth::user()->folios > 0)
			{
			    
				$nuevosFolios = Auth::user()->folios - 1;
				$input = Usuarios_sistema::findOrFail(Auth::id());
				$usuario['folios'] = $nuevosFolios;
				$input -> update($usuario);   
			
			}
		}
				
	}else
	{
			if(Auth::user()->folios > 0)
			{
				$nuevosFolios = Auth::user()->folios - 1;
				$input = Usuarios_sistema::findOrFail(Auth::id());
				$usuario['folios'] = $nuevosFolios;
				$input -> update($usuario);   
			}	
	}		 
	   
	 
	  /*Sino quitar... 
	   
	   
	$nuevosFolios = Auth::user()->folios - 1;
	$input = Usuarios_sistema::findOrFail(Auth::id());
	$usuario['folios'] = $nuevosFolios;
	$input -> update($usuario);   */
	
	
	if($request->get('c_cliente') > 1)
	{
		
	$factura = CFDI::where('id', '=', $id)->first();
		
	$reporte = new Reportes(); 
	$pdf = $reporte -> crearPDF($factura); 
	$user = Clientes::where('id','=',$request->get('c_cliente'))->first();
	
	Mail::send('emails.invoice',['user' => $user], function ($m) use ($user,$factura,$pdf) 
	{$m->to($user->correo, $user->nombrecompleto)->subject('Factura electrónica')
	->attachData($factura->factura, "factura.xml",['as' => 'factura.xml', 'mime' => 'factura.xml'])
	->attachData($pdf->output(), "factura.pdf");});
	
		$mensaje = "Ha recibido una factura de ".$factura->emisor." por un monto de ".$factura->total_facturado." con el UUID ".$factura->uuid.". Buena dia. quimerasystems.com";
		
		$mensaje =  utf8_encode($mensaje);
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
    $mensaje = preg_replace(array_keys($utf8), array_values($utf8), $mensaje);  
		  
		   try {
          $account_sid = 'ACb16dfc84712f3d2267086740065a5df9'; 
          $auth_token = '56aa0f96b755746b1339db2d195aa260'; 
	
          $client = new Services_Twilio($account_sid, $auth_token); 
          $client->account->messages->create(array(  
          'To' => "+52".$user -> telefono, 
	      'From' => "+525549998797",    
	      'Body' => $mensaje,  
           ));
		  
		   } catch (Services_Twilio_RestException $e) {
           return redirect('invoices/show');
		  }
    		
	}  
	   
		return redirect('invoices/show');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function storeReturn(InvoiceRequest $request)
	{
		
	$banderaFactura = false;
	
	if(Auth::user()->suscripcion > 0)
	{
		$suscripcion = Suscripcion::where('idusuario', '=', Auth::user()->id)->where('tipo','=','1')->where('status','=','0')->first();
		if($suscripcion->itemsConsumidos<$suscripcion->itemsAsignados)
		{
		$banderaFactura = true;	
		}else
		{
			if(Auth::user()->folios > 0)
			{
			$banderaFactura = true;
			}else
			{
			$banderaFactura = false;	
			}	
		}
				
	}else
	{
			if(Auth::user()->folios > 0)
			{
			$banderaFactura = true;
			}else 
			{
			$banderaFactura = false;	
			}		
	}		
		
		
		
	if($banderaFactura == false)
	{
	return redirect()->back()->withErrors(['Lo sentimos no cuentas con folios para realizar esta factura']);	
	}	
	
	
	
	
	   $factura = new DocumentoNota;
       $arregloDocumento = $factura->crearDocumento($request);
	   $id = $factura->crearFactura($arregloDocumento);
	   
	   
	  if(Auth::user()->suscripcion > 0)
	{
		$suscripcion = Suscripcion::where('idusuario', '=', Auth::user()->id)->where('tipo','=','1')->where('status','=','0')->first();
		if($suscripcion->itemsConsumidos<$suscripcion->itemsAsignados)
		{
			    $suscripcionItems = [];
				$nuevosFolios = $suscripcion->itemsConsumidos;
				$nuevosFolios = $nuevosFolios + 1;
				$input = Suscripcion::findOrFail($suscripcion->id);
				$suscripcionItems['itemsConsumidos'] = $nuevosFolios;
				$input -> update($suscripcionItems);
		}else
		{
			if(Auth::user()->folios > 0)
			{
			    
				$nuevosFolios = Auth::user()->folios - 1;
				$input = Usuarios_sistema::findOrFail(Auth::id());
				$usuario['folios'] = $nuevosFolios;
				$input -> update($usuario);   
			
			}
		}
				
	}else
	{
			if(Auth::user()->folios > 0)
			{
				$nuevosFolios = Auth::user()->folios - 1;
				$input = Usuarios_sistema::findOrFail(Auth::id());
				$usuario['folios'] = $nuevosFolios;
				$input -> update($usuario);   
			}	
	}		 
	   
	 

	
	
	if($request->get('c_cliente') > 1)
	{
		
	$factura = CFDI::where('id', '=', $id)->first();
		
	$reporte = new Reportes(); 
	$pdf = $reporte -> crearPDF($factura); 
	$user = Clientes::where('id','=',$request->get('c_cliente'))->first();
	
	Mail::send('emails.invoice',['user' => $user], function ($m) use ($user,$factura,$pdf) 
	{$m->to($user->correo, $user->nombrecompleto)->subject('Factura electrónica')
	->attachData($factura->factura, "factura.xml",['as' => 'factura.xml', 'mime' => 'factura.xml'])
	->attachData($pdf->output(), "factura.pdf");});
	
		$mensaje = "Ha recibido una factura de ".$factura->emisor." por un monto de ".$factura->total_facturado." con el UUID ".$factura->uuid.". Buena dia. quimerasystems.com";
		
		$mensaje =  utf8_encode($mensaje);
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
    $mensaje = preg_replace(array_keys($utf8), array_values($utf8), $mensaje);  
		  
		   try {
          $account_sid = 'ACb16dfc84712f3d2267086740065a5df9'; 
          $auth_token = '56aa0f96b755746b1339db2d195aa260'; 
	
          $client = new Services_Twilio($account_sid, $auth_token); 
          $client->account->messages->create(array(  
          'To' => "+52".$user -> telefono, 
	      'From' => "+525549998797",    
	      'Body' => $mensaje,  
           ));
		  
		   } catch (Services_Twilio_RestException $e) {
           return redirect('invoices/show');
		  } 
    		
	}  
	   
		return redirect('invoices/show');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$data = [];
		$resultados = CFDI::where('idusuario', '=', Auth::id())->orderBy('id','desc')->paginate(10); 
		/* $resultados = CFDI::where('id', '>', '0')->paginate(10); */
		$data['resultados'] = $resultados;		
		$data['folios'] = 0;
	if(Auth::user()->suscripcion > 0)
	{
		$suscripcion = Suscripcion::where('idusuario', '=', Auth::user()->id)->where('tipo','=','1')->where('status','=','0')->first();
		$data['folios'] = ($suscripcion -> itemsAsignados - $suscripcion -> itemsConsumidos) + Auth::user()->folios;		
	}else
	{
	$data['folios'] = Auth::user()->folios;	
	}			
	return view('invoices.show',$data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function printXML($id)
	{
		$factura = CFDI::where('id', '=', $id)->first();
		
		$response = Response::make($factura->factura, 200);
		$response->header('Cache-Control', 'public');
		$response->header('Content-Description', 'File Transfer');
		$response->header('Content-Disposition', 'attachment; filename='.$factura->uuid.'.xml');
		$response->header('Content-Transfer-Encoding', 'binary');
		$response->header('Content-Type', 'text/xml');
		return $response;
		}
	
	public function printCancelXML($id)
	{
		$factura = CFDI::where('id', '=', $id)->first();
		
		$response = Response::make($factura->cancelacion, 200);
		$response->header('Cache-Control', 'public');
		$response->header('Content-Description', 'File Transfer');
		$response->header('Content-Disposition', 'attachment; filename='.$factura->uuid.'_cancelacion.xml');
		$response->header('Content-Transfer-Encoding', 'binary');
		$response->header('Content-Type', 'text/xml');
		return $response;	
	}
	
	public function printPDF($id)
	{
	
		$factura = CFDI::where('id', '=', $id)->first();
		
		$reporte = new Reportes(); 
		$pdf = $reporte -> crearPDF($factura); 
		return $pdf -> stream();
		}


	public function printCancelPDF($id)
	{
		$data = [];
		$cancelacion = CFDI::where('id', '=', $id)->first();
		
		$xmlstr = $cancelacion -> cancelacion; 
		$firma=explode("SignatureValue", $xmlstr);
		$firmaDigital=$firma[1];
		$firmaDigital=substr($firmaDigital, 0, -2);
		$firmaDigital=substr($firmaDigital, 1);
		
		$data['SignatureValue'] = $firmaDigital;
		$data['Hora'] = $cancelacion->updated_at;
		$data['Emisor'] = $cancelacion -> emisor;
		$data['uuid'] = $cancelacion -> uuid;
		
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
		
		$pdf = PDF::loadView('formatos.c_'.$nombre_formato,$data);
        //return $pdf->download('invoice.pdf');
		return $pdf -> stream();
	}  


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 
	public function showFormats()
	{
		$data = [];
		$resultados = FormatosCFDI::where('disponibilidad', '=', '0')->paginate(10);
		$resultados2 = DB::table('formatos_cfdi')
            ->join('formatos_usuario', 'formatos_cfdi.id', '=', 'formatos_usuario.idformato')
            ->select('formatos_cfdi.id', 'formatos_cfdi.nombre_Formato')->get();
			
		$resultados3 = DB::table('formatos_cfdi')
        ->leftJoin('formatos_usuario', 'formatos_cfdi.id', '=', 'formatos_usuario.idformato')
		->whereNull('formatos_usuario.idformato')
		->where('formatos_cfdi.disponibilidad','=','1')
		->select('formatos_cfdi.id', 'formatos_cfdi.nombre_Formato','formatos_cfdi.importe')
        ->paginate(4);
            
		
		
		$formatoSeleccionado = Usuarios_sistema::where('id', '=', Auth::id())->first();
		 
		$data['resultados'] = $resultados;
		$data['resultados2'] = $resultados2;
		$data['resultados3'] = $resultados3;
		$data['formatoSeleccionado'] = $formatoSeleccionado->formato; 
		
		return view('invoices.formats',$data);
	} 
	
		public function updateFormat(FormatRequest $request)
	{
		
		$input = Usuarios_sistema::findOrFail(Auth::id());	
		$input -> update($request -> all());
		return redirect('home');	 
	}
	
 

	public function cancel($id)
	{
	   $cancelacion = new Cancelacion_Documento;
       $mensaje = $cancelacion->cancelaDocumento($id);
	   //  return  var_dump($mensaje);
	   //  return $mensaje;
	   return redirect('invoices/show');
	}
	
	public function createAddenda($id)
	{
	
	$data = [];
		
	$addendas = Addenda::where('id', '>', '0')
	->lists('nombre','id');
		
	$data['adds'] = $addendas;
	$data['cfdi'] = $id;			
	return view('invoices.addenda',$data);	
	}

    public function addAddenda()
	{
		
	  if(Request::ajax()) {
	  $addenda = Addenda::where('id', '=', Request::get('num_addenda'))->first();	  
      print_r($addenda -> addenda);die;
      return;
	  }
	}	
	
	public function storeAddenda(\Illuminate\Http\Request $request)
	{
	
	$xmlBase = CFDI::findOrFail($request->get('cfdi'));
	$xml = new DOMDocument( "1.0","UTF-8" );
    $xml->loadXML($xmlBase->factura);
	$root = $xml->documentElement; 
	$addenda = $xml -> createDocumentFragment();		
	$texto_addenda = str_replace("@", "\"", $request->get('addenda'));
	$addenda -> appendXML($texto_addenda);
	$root->appendChild($addenda); 
	
	$data = [];
	$data['factura'] = $xml->saveXML(); 
	$data['addenda'] = $texto_addenda;
	$data['estado_addenda'] = 1;
	
	$xmlBase -> update($data);	
		
	return redirect('invoices/show');	 
	}
	 
	 
	public function destroy($id)
	{
		//
	}

}

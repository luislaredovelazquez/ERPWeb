<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Usuarios_sistema;
use App\Compras;
use App\Recordatorio;



class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('welcome');
	}
	
	public function terms()
	{
		return view('terms');
	}
	
	public function privacy()
	{
		return view('privacy');
	}
	
	public function createWeb()
	{
		return view('createWeb');
	}
	
	public function voiceReminder($resource)
	{
	$recordatorio = Recordatorio::where('recurso', '=', $resource)->first();
	if(!empty($recordatorio))
	{
	$data = [];
	$data['recordatorio'] = Storage::disk('local')->get($recordatorio->idusuario.'/'.$resource.'.txt');
	return view('reminders.voice',$data);
	}
	else
	return redirect('/');
		
	}
	
	public function wc()
	{
		
	
	
	$body = @file_get_contents('php://input');
	$event = json_decode($body);
	
	if($event->type == 'charge.paid')
	{
	// $charge = $event->data->object;
	
	$piezas = explode("_", $event -> reference_id);
	$compra = Compras::where('id_usuario','=',$piezas[3])
					->where('referencia','=',$piezas[1])
					->orderBy('id', 'desc')->first();
					
	//Sólo para probar que entró aquí
	
	$usuario = Usuarios_sistema::findOrFail($piezas[3]);
	$datos_usuario = [];
	$datos_usuario['folios'] = 0;	
	$usuario -> update($datos_usuario);
	
	//Sólo para probar que entró aquí				
					
	$datos_compra = [];
	$datos_compra['status'] = 'Pagado';
	$compra -> update($datos_compra);
	
	if($piezas[0]=='E') //Si es un folio
	{
	$usuario = Usuarios_sistema::findOrFail($piezas[3]);
	$datos_usuario = [];
	$datos_usuario['folios'] = $usuario -> folios + $compra -> cantidad;	
	$usuario -> update($datos_usuario);
	}elseif($piezas[0]=='R') //Si es un recordatorio
	{
	$usuario = Usuarios_sistema::findOrFail($piezas[3]);
	$datos_usuario = [];
	$datos_usuario['recordatorios'] = $usuario -> recordatorios + $compra -> cantidad;	
	$usuario -> update($datos_usuario);
	}elseif($piezas[0]=='F') //Si es un formato
	{
	$nuevo = [];
	$nuevo['idusuario'] = $piezas[3];
	$nuevo['idformato'] = $piezas[4];
	FormatosUsuario::create($nuevo);
	}
	
	echo "Listo";				
		
	}
	
	return header('HTTP/1.1 200 OK');
		
		}
	

}

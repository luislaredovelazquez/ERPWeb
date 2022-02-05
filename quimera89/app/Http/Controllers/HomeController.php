<?php namespace App\Http\Controllers;

use App\CFDI;
use App\Clientes;
use Auth;
use DB;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
			
		//Últimas facturas y últimas cancelaciones	
				
		$resultado =  DB::table('cfdi')
        ->join('clientes', 'cfdi.receptor', '=', 'clientes.rfc')
		->where('cfdi.idusuario','=',Auth::id())
		->where('cfdi.estado','=','0')		
		->select('clientes.id','clientes.nombrecompleto', 'cfdi.total_facturado',
				           'cfdi.created_at')
		->orderBy('cfdi.id', 'DESC')				   
        ->take(5) 
        ->get();	
		
		$resultado2 =  DB::table('recordatorio')
        ->join('clientes', 'recordatorio.idcliente', '=', 'clientes.id')
		->where('recordatorio.idusuario','=',Auth::id())
		->where('recordatorio.status','=','0')		
		->select('clientes.id','clientes.nombrecompleto', 'recordatorio.tipo',
				           'recordatorio.created_at')
		->orderBy('recordatorio.id', 'DESC')				   
        ->take(5) 
        ->get();	
		
		//Checar número de facturas
		
		$resultado3 =  DB::table('cfdi')
		->where('idusuario', '=', Auth::id())
		->where('estado', '=', '0')
        ->count();
		
		
		$resultado5 =  DB::table('cfdi')
		->where('idusuario', '=', Auth::id())
		->where('estado', '=', '1')
        ->count();
		
		
		//Checar montos de facturas
			
		$resultado4 =  DB::table('cfdi')
		->where('idusuario', '=', Auth::id())
		->where('estado', '=', '0')
        ->sum('total_facturado');	
		
		
		$resultado6 =  DB::table('cfdi')
		->where('idusuario', '=', Auth::id())
		->where('estado', '=', '1')
        ->sum('total_facturado');	
		
		
		//Clientes 
		$resultado7 =  DB::table('cfdi')
		->where('idusuario', '=', Auth::id())
		->where('receptor', '=', 'XAXX010101000')
        ->sum('total_facturado');	
		
		$resultado8 =  DB::table('cfdi')
		->where('idusuario', '=', Auth::id())
		->where('receptor', '!=', 'XAXX010101000')
        ->sum('total_facturado');
		
		
		$data['invoice_items'] = $resultado;
		$data['cancel_items'] = $resultado2;
		
		
		$data['facturas_vigentes'] = $resultado3;
		$data['facturas_canceladas'] = $resultado5;
		
		
		$data['montos_vigentes'] = $resultado4;
        $data['montos_cancelados'] = $resultado6;	
		
		
		//Clientes			
	    $data['publico_general'] = $resultado7;
		$data['clientes'] = $resultado8;
		
		
		return view('home',$data);
	}

}

<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request; 

// use Request;
use Auth;
use DB;

use App\Clientes;
use App\Encuesta_Clientes;

class ClientController extends Controller {
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}
	

	public function create()
	{
      return view('clients.create');
	}
	
	public function store(ClientRequest $request)
	{
		// $input =  Request::get('name');
		$input =  $request->all();
		$input['idusuario'] = Auth::id();
		if($input['cuenta'] == '')
		$input['cuenta'] = 'NO APLICA';
		Clientes::create($input);
		return redirect('clients/showClients');
	}
	
	public function edit($id)
	{
		$data = [];	
		$cliente = Clientes::findOrFail($id);
		$data['cliente'] = $cliente;
		return view('clients.edit',$data);
	}
	
	public function update($id,ClientRequest $request)
	{
		$input = Clientes::findOrFail($id);	
		$input -> update($request -> all());
		return redirect('clients/showClients');			
	}
	
	public function show()
	{
		//Datos estáticos
		/* $data=[];
		$data['nombre'] = 'Luis';
		$data['apellido'] = 'Laredo'; */
		
		
		//Datos directos de la BD
		/* $results = DB::select('select * from users where id = ?', [1]);
		$data = [];
		$data['resultados'] = $results; */
		
		//Eloquent
		$data = [];
		//$resultados = Clientes::latest()->get();
		$resultados = Clientes::where('idusuario', '=', Auth::id())->paginate(10);
		//$resultados = Clientes::order_by('published_at','desc')->get();
		$data['resultados'] = $resultados;
		
		return view('clients.show',$data);
	}
	
	public function saveQuiz(Request $request)
	{
		$input =  $request->all();
		$input['idusuario'] = Auth::id();
		Encuesta_Clientes::create($input);
		return redirect($request->get('redirect'));
		
	}

}

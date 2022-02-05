<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReminderRequest;
use App\Clientes;
use App\Recordatorio;
use App\AcortarURL;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Crypt;
use App\usuarios_sistema;
use Mail;

class ReminderController extends Controller {

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
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$data = [];
		$clientes = Clientes::where('idusuario', '=', Auth::id())
		->lists('nombrecompleto','id');
				
		$data['clientes'] = $clientes;
		return view('reminders.create',$data);
	}
	
	public function createOpen()
	{
		$data = [];
		$clientes = Clientes::where('idusuario', '=', Auth::id())
		->lists('nombrecompleto','id');
				
		$data['clientes'] = $clientes;
		return view('reminders.createOpen',$data);
	}
	
		public function createBirthday()
	{
		$data = [];
		$clientes = Clientes::where('idusuario', '=', Auth::id())
		->lists('nombrecompleto','id');
				
		$data['clientes'] = $clientes;
		return view('reminders.createBirthday',$data);
	}
	
	public function createVoice()
	{
		$data = [];
		$clientes = Clientes::where('idusuario', '=', Auth::id())
		->lists('nombrecompleto','id');
				
		$data['clientes'] = $clientes;
		return view('reminders.createVoice',$data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ReminderRequest $request)
	{
		$input = [];
		$input['idusuario'] = Auth::id();
		$input['idcliente'] = $request->get('c_cliente');
		$input['fechainicio'] = $request->get('fechainicio');
		$input['fechaactual'] = $request->get('fechainicio');
		$input['fechafinal'] = $request->get('fechafinal');
		$input['lapso'] = $request->get('lapso');
		$input['motivo'] = $request->get('motivo');
		$input['monto'] = $request->get('monto');
		$input['tipo'] = $request->get('tipo');
		$input['status'] = '0';
		$input['recurso'] = '';
		$input['sms'] = $request->get('c_sms');
		$input['hora'] = $request->get('c_hora');
		
		Recordatorio::create($input);
		return redirect('reminders/show');
	}
	
		public function storeOpen(ReminderRequest $request)
	{
		$input = [];
		$input['idusuario'] = Auth::id();
		$input['idcliente'] = $request->get('c_cliente');
		$input['fechainicio'] = $request->get('fechainicio');
		$input['fechaactual'] = $request->get('fechainicio');
		$input['fechafinal'] = $request->get('fechafinal');
		$input['lapso'] = $request->get('lapso');
		$input['motivo'] = '';
		$input['monto'] = '';
		$input['tipo'] = $request->get('tipo');
		$input['status'] = '0';
		$input['recurso'] = $request->get('descripcion');
		$input['sms'] = $request->get('c_sms');
		$input['hora'] = $request->get('c_hora');
		
		Recordatorio::create($input);
		return redirect('reminders/show');
	}
	
		public function storeBirthdayReminder(ReminderRequest $request)
	{
		$input = [];
		$input['idusuario'] = Auth::id();
		$input['idcliente'] = $request->get('c_cliente');
		$input['fechainicio'] = $request->get('fechainicio');
		$input['fechaactual'] = $request->get('fechainicio');
		$input['fechafinal'] = $request->get('fechafinal');
		$input['lapso'] = $request->get('lapso');
		$input['motivo'] = '';
		$input['monto'] = '';
		$input['tipo'] = $request->get('tipo');
		$input['status'] = '0';
		$input['recurso'] = '';
		$input['sms'] = $request->get('c_sms');
		$input['hora'] = $request->get('c_hora');
		
		Recordatorio::create($input);
		return redirect('reminders/show');
	}
	
	public function storeVoiceReminder(ReminderRequest $request)
	{	
		
		$input = [];
		$input['idusuario'] = Auth::id();
		$input['idcliente'] = $request->get('c_cliente');
		$input['fechainicio'] = $request->get('fechainicio');
		$input['fechaactual'] = $request->get('fechainicio');
		$input['fechafinal'] = $request->get('fechafinal');
		$input['lapso'] = $request->get('lapso');
		$input['motivo'] = '';
		$input['monto'] = '0.00';
		$input['tipo'] = $request->get('tipo');
		$input['status'] = '0';
		$input['recurso'] = '';
		$input['sms'] = $request->get('c_sms');
		$input['hora'] = $request->get('c_hora');
		$recordatorio = Recordatorio::create($input);
		
		
		
		$input = Recordatorio::findOrFail($recordatorio->id);
		$acortar = new AcortarURL();
		
		$shortURL = $acortar->alphaID($recordatorio->id,false,5,'donatello');
		
		$data['recurso'] = $shortURL;
		$input -> update($data);
				
		// $request->get('audioGuardado');
		$audio = $request->get('audioGuardado');
		Storage::disk('local')->put(Auth::id().'/'.$shortURL.'.txt',$audio);
				
		return redirect('reminders/show');
	}
		

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$data = [];
		// $resultados = Recordatorio::where('idusuario', '=', Auth::id())->orderBy('id','desc')->paginate(10); 
		/* $resultados = CFDI::where('id', '>', '0')->paginate(10); */
		$resultados = DB::table('recordatorio')
        ->join('clientes', function($join)
        {
            $join->on('recordatorio.idcliente', '=', 'clientes.id')
                 ->where('recordatorio.idusuario', '=', Auth::id());
        })
		->select('clientes.nombrecompleto as nombrecompleto', 'recordatorio.tipo as tipo',
				  'recordatorio.fechainicio as fechainicio','recordatorio.fechaactual as fechaactual',
				  'recordatorio.fechafinal as fechafinal','recordatorio.id as id','recordatorio.status as status')
         ->orderBy('recordatorio.status','asc')->paginate(10); 
		
		
		$data['resultados'] = $resultados;		
		$data['recordatorios'] = Auth::user()->recordatorios;
		return view('reminders.show',$data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data = [];
		$resultados = DB::table('recordatorio')
        ->join('clientes', function($join) use ($id)
        {
            $join->on('recordatorio.idcliente', '=', 'clientes.id')
                 ->where('recordatorio.id', '=', $id);
        })
		->select('clientes.nombrecompleto as nombrecompleto','clientes.id as idClie',
			      'recordatorio.tipo as tipo','recordatorio.fechainicio as fechainicio',
			      'recordatorio.fechaactual as fechaactual','recordatorio.fechafinal as fechafinal',
			      'recordatorio.lapso as lapso','recordatorio.motivo as motivo',
			      'recordatorio.monto as monto','recordatorio.id as id','recordatorio.recurso as recurso')
        ->first(); 
		
		if($resultados->tipo == 3)
		$data['audio'] = Storage::disk('local')->get(Auth::id().'/'.$resultados->recurso.'.txt');
		
		$data['resultados'] = $resultados;		
		return view('reminders.edit',$data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function cancel($id)
	{
		$input = Recordatorio::findOrFail($id);
		$data = [];
		$data['status'] = '1';
		$input->update($data);
		return redirect('reminders/show');
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}

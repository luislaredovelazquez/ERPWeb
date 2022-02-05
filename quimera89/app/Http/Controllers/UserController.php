<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserSatRequest;
use App\Http\Requests\FilesRequest;
use App\Http\Requests\ProfileRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\InformacionFiscal;
use App\ArchivosFiscales;
use App\usuarios_sistema;
use Auth;
use Hash;
use Validator;
use Conekta;
use Conekta_Customer;
use Conekta_Error;

class UserController extends Controller {

		public function __construct()
	{
		$this->middleware('auth');
	}
	
		public function edit()
	{
		$data = [];	
		$usuario = Usuarios_sistema::findOrFail(Auth::id());
		$data['usuario'] = $usuario;
		return view('users.edit',$data);
	}
	
	   
	public function update(ProfileRequest $request)
	{
		$id_usuario = "";
		$input = Usuarios_sistema::findOrFail(Auth::id());
		$usuario = [];
		
		
		 if($request -> get('conektaTokenId') != '')
			{
		

		
		if(Auth::user()->customer_key == ''){
		try{
  			$customer = Conekta_Customer::create(array(
    		"name"=>  $request -> get('name'),
    		"email"=> $input->email,
    		"phone"=> $request -> get('telefono'),
    		"cards"=>  array($request -> get('conektaTokenId'))
		//	"cards"=>  array($_POST['conektaTokenId'])
 		//"tok_a4Ff0dD2xYZZq82d9"
  			));
			
		$id_usuario = $customer->id;	
		$usuario['customer_key'] = $id_usuario;
		}catch (Conekta_Error $e){
  		// return $e->getMessage();
  		return redirect()->back()->withErrors([$e->message_to_purchaser]);
 		//el cliente no pudo ser creado
		}
		
	}else{
		$customer = Conekta_Customer::find(Auth::user()->customer_key);
		$customer->update(
  		array(
    		'name'  => $request -> get('name'),
	  		'email' => $input->email,
	  		'phone' => $request -> get('telefono'),
  		)); 
		$card = $customer->cards[0]->update(array('token' => $request -> get('conektaTokenId'), 'active' => false));
	}
		
		}		
		
		$usuario['forma_pago'] = $request -> get('opPago');
		$usuario['name'] = $request -> get('name');
		$usuario['telefono'] = $request -> get('telefono');
					
		$input -> update($usuario);
		return redirect('home');			
	}
	
	public function editFiscal()
	{
		$data = [];	
		$usuario = InformacionFiscal::where('idusuario', '=', Auth::id())->first();
		$data['usuario'] = $usuario;
		return view('users.fiscal',$data);
	}
	
	public function updateFiscal(UserSatRequest $request)
	{
	$input = InformacionFiscal::where('idusuario', '=', Auth::id())->first();
	$input -> update($request -> all());
		
		$rfc = InformacionFiscal::where('rfc', '=', $request->get('rfc'))->first();
		if($rfc == null)
		{
		//Permisos PAC
		
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
     $res = $client->otorgarAccesoContribuyente(
          array(
          )
     );
} catch ( Exception $e ) {
     print_r('Failed WS: code - ' . $e->getCode() . '- [' . $e->getFile() . ']line: ' . $e->getLine() . ', '  . $e->getMessage() );
}

		
		
		
		return redirect('home');			
		
		}
		else
		return redirect('home');
		
		
 

	}
	
		public function editFiles()
	{
		$data = [];	
		$usuario = ArchivosFiscales::where('idusuario', '=', Auth::id())->first();
		$data['usuario'] = $usuario;
		return view('users.files',$data);
	}
	
	public function updateFiles(FilesRequest $request)
	{
		$input = ArchivosFiscales::where('idusuario', '=', Auth::id())->first();
		$datos = $request->all();
		
		
		$fileLogo = $request->file('fileLogo');
		$extension = $fileLogo->getClientOriginalExtension();
		Storage::disk('local')->put(Auth::id().'/'.$fileLogo->getClientOriginalName(),  File::get($fileLogo));	
		
		$datos['logo'] = $fileLogo->getClientOriginalName();
		$datos['logoSistema'] = $fileLogo->getFilename().'.'.$extension;
		$datos['logoMime'] = $fileLogo->getClientMimeType();
		
		$fileCer = $request->file('fileCer');
		$extension = $fileCer->getClientOriginalExtension();		
		
		Storage::disk('local')->put(Auth::id().'/'.$fileCer->getClientOriginalName(),  File::get($fileCer));
		
		
		/*Empieza*/
        $certificateCAcer = $fileCer->getClientOriginalName();
        $certificateCAcerContent = Storage::disk('local')->get(Auth::id().'/'.$certificateCAcer); 
        $certificateCApemContent =  '-----BEGIN CERTIFICATE-----'.PHP_EOL
        .chunk_split(base64_encode($certificateCAcerContent), 64, PHP_EOL)
        .'-----END CERTIFICATE-----'.PHP_EOL;
        $certificateCApem = $certificateCAcer.'.pem';
		/*Termina*/
        Storage::disk('local')->put(Auth::id().'/'.$certificateCApem, $certificateCApemContent); 
			
		
		$datos['certificado'] = $fileCer->getClientOriginalName();
		$datos['certificadoSistema'] = $fileCer->getFilename().'.'.$extension;
		
	    $fileKey = $request->file('fileKey');
		$extension = $fileKey->getClientOriginalExtension();
		
		Storage::disk('local')->put(Auth::id().'/'.$fileKey->getClientOriginalName()/*.'.'.$extension*/,  File::get($fileKey));
		
		 
		/* empieza quizás en modo productivo se tendrá que quitar el .. y cambiar por storage/app/ tomando en cuenta
		 * que el directorio raíz podría ser index  */
		$storageDir = '../storage/app/'.Auth::id().'/';
		$keyPem = $storageDir.$fileKey->getClientOriginalName();
		$output = shell_exec('openssl pkcs8 -inform DER -in '.$keyPem.' -out '.$keyPem.'.pem -passin pass:'.$request->get('contrasena')); 
		/* 
		 * Quizás se tendrá que agregar la siguiente sentencia en ambiente productivo
		 * $output = shell_exec('openssl rsa -in '.$keyPem.'.pem -out '.$keyPem.'.pem');
		 * de preferencia no hacerlo, termina */

		
		
		
		$datos['llave'] = $fileKey->getClientOriginalName();
		$datos['llaveSistema'] = $fileKey->getFilename().'.'.$extension;
				
		$input -> update($datos);
		
		return redirect('home');			
	}
	
	public function editPwd()
	{
		/*$input = Usuarios_sistema::findOrFail(Auth::id());	
		$input -> update($request -> all()); */
		return view('users.changePassword');			
	}
	
	public function updatePwd(Request $request)
	{
		 $v = Validator::make($request->all(), [
		'old_password' => 'required',
        'password' => 'required|confirmed|min:6'
    ]);
	
	 if ($v->fails())
    {
        return redirect()->back()->withErrors($v->errors());
    }
		
		$oldPwd = $request -> get('old_password');		
		
		if (Auth::attempt(['email' => Auth::user() -> email, 'password' => $oldPwd]))
        {
        $user = Usuarios_sistema::findOrFail(Auth::user()->id);
        $user->password = Hash::make($request -> get('password'));
        $user->save();	
		return redirect('home');	
        }else
		{
		return redirect()->back()->withErrors('El password anterior no coincide con nuestra base de datos');	
		}


	}
	
		public function confirm()
	{
		return view('users.successPassword');
	}	

}

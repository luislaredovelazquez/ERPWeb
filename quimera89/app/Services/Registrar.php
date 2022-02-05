<?php namespace App\Services;

use App\usuarios_sistema;
use App\InformacionFiscal;
use App\ArchivosFiscales;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:usuarios',
			'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		$user =  Usuarios_sistema::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'puesto' => 'visitante',
			'bienvenida' => '0',
			'telefono' => '',
			'forma_pago' => '0',
			'estado' => '0',
			'folios' => '0',
			'recordatorios' => '0',
			'suscripcion' => '0',
			'fechaSucripcion' => '0000-00-00 00:00:00',
			'password' => bcrypt($data['password'])
		]);
		
		$lastInsertedId= $user->id;
		
		InformacionFiscal::create([
			'idUsuario' => $lastInsertedId,
			'rfc' => '',
			'codigoPostal' => '',
			'pais' => '',
			'estadoRepublica' => '',
			'municipio' => '',
			'localidad' => '',
			'colonia' => '',
			'noInterior' => '',
			'noExterior' => '',
			'calle' => '',
			'e_codigoPostal' => '',
			'e_pais' => '',
			'e_estado' => '',
			'e_municipio' => '',
			'e_localidad' => '',
			'e_colonia' => '',
			'e_noInterior' => '',
			'e_noExterior' => '',
			'e_calle' => '',
			'regimen' => '',				
		]);
		
		ArchivosFiscales::create([
			'idusuario' => $lastInsertedId,
			'logo' => '',
			'logoSistema' => '',
			'logoMime' => '',
			'certificado' => '',
			'certificadoSistema' => '',
			'llave' => '',
			'llaveSistema' => '',
			'contrasena' => '',
			'num_certificado' => '',
		]);		
		
		Storage::makeDirectory($lastInsertedId);
		
		return $user;
		
	}

}

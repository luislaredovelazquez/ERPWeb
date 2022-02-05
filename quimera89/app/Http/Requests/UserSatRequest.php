<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserSatRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'rfc' => 'required',
			'codigoPostal' => 'required',
			'pais' => 'required',
			'estadoRepublica' => 'required',
			'municipio' => 'required',
			'localidad' => 'required',
			'colonia' => 'required',
			'noInterior' => 'required',
			'noExterior' => 'required',
			'calle' => 'required',
			'e_codigoPostal' => 'required',
			'e_pais' => 'required',
			'e_estado' => 'required',
			'e_municipio' => 'required',
			'e_localidad' => 'required',
			'e_colonia' => 'required',
			'e_noInterior' => 'required',
			'e_noExterior' => 'required',
			'e_calle' => 'required',
			'regimen' => 'required',			
		];
	}

}

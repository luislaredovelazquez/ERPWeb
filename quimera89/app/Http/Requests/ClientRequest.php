<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ClientRequest extends Request {

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
		    'telefono' => 'min:10|max:10',
		    'cuenta' => 'min:4',
		    'correo' => 'email',
			'nombrecompleto' => 'required',
			'codigoPostal' => 'required',
			'pais' => 'required',
			'estado' => 'required',
			'colonia' => 'required',
			'calle' => 'required',
		];
	}

}

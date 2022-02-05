<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class InvoiceRequest extends Request {

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
		/*	
			'metodoDePago' => 'required',
			'tipoDeComprobante' => 'required',
			'total' => 'required',
			'Moneda' => 'required',
			'descuento' => 'required',
			'subTotal' => 'required',
		    'formaDePago' => 'required',
			'fecha' => 'required',				 
			'c_importe' => 'required',
			'c_cantidad' => 'required',
			'totalImpuestosTrasladados' => 'required',
			'importe' => 'required',
			'tasa' => 'required',
			'impuesto' => 'required', 
			'formaDePago' => 'required',*/
		];
	}

}

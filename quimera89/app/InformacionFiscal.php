<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class InformacionFiscal extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'informacion_fiscal';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
	        'idUsuario',
			'rfc',
			'codigoPostal',
			'pais',
			'estadoRepublica',
			'municipio',
			'localidad',
			'colonia',
			'noInterior',
			'noExterior',
			'calle',
			'e_codigoPostal',
			'e_pais',
			'e_estado',
			'e_municipio',
			'e_localidad',
			'e_colonia',
			'e_noInterior',
			'e_noExterior',
			'e_calle',
			'regimen',
	];
	
	
	
}

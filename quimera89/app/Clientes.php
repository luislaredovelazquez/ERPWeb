<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	 protected $fillable = [
	 
	 'idusuario',
	 'correo',
	 'telefono',
	 'nombrecompleto',
	 'rfc',
	 'codigoPostal',
	 'pais',
	 'estado',
	 'colonia',
	 'noInterior',
	 'noExterior',
	 'calle',
	 'cuenta',
	 ];
	 
	protected $table = 'clientes';
	

}

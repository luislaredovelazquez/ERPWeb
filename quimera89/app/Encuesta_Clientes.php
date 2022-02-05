<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Encuesta_Clientes extends Model {

	  protected $fillable = [
	 'idusuario',
	 'idcliente',
	 'idpregunta',
	 'respuesta'	 
	 ];
	 
	protected $table = 'encuesta_clientes';
}

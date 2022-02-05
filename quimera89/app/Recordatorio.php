<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model {

	protected $fillable = [
	 
	 'idusuario',
	 'idcliente',
	 'fechainicio',
	 'fechaactual', 
	 'fechafinal',
	 'lapso',
	 'motivo',
	 'monto',
	 'tipo',
	 'status',
	 'recurso',
	 'sms',
	 'hora'	 
	 ];
	 
	protected $table = 'recordatorio';

}

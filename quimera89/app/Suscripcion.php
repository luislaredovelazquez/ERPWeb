<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model {

     protected $fillable = [
	 'idusuario',
	 'inicioSuscripcion',
	 'proximaFecha',
	 'finSuscripcion',
	 'itemsAsignados',
	 'itemsConsumidos',
	 'tipo',
	 'status',
	 ];
	 
	protected $table = 'suscripcion';

}

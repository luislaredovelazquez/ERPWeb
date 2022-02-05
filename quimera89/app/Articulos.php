<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulos extends Model {

	 protected $fillable = [
	 
	 'codigo',
	 'descripcion',
	 'precioVenta',
	 'idusuario', 
	 'unidad'	 
	 ];
	 
	protected $table = 'articulos';

}

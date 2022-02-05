<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Compras extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	 protected $fillable = [
	 
	 'id_usuario',
	 'descripcion',
	 'cantidad',
	 'tipo_pago',
	 'ultimos4',
	 'subtotal',
	 'iva',
	 'total',
	 'referencia',
	 'status'
	 
	 ];
	 
	protected $table = 'compras';

}

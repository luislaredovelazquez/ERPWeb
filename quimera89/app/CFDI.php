<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CFDI extends Model {

     protected $fillable = [
	 'factura',
	 'folio',
	 'idusuario',
	 'emisor',
	 'receptor',
	 'total_facturado',
	 'uuid',
	 'cadena_original',
	 'observaciones',
	 'estado',
	 'addenda',
	 'estado_addenda',
	 'cancelacion',
	 ];
	 
	protected $table = 'cfdi';

}

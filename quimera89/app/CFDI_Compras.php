<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CFDI_Compras extends Model {

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
	 'cancelacion',
	 ];
	 
	protected $table = 'cfdi_compras';

}

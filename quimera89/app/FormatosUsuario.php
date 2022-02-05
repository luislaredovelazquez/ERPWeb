<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class FormatosUsuario extends Model {

    protected $fillable = [
	 
	 'idusuario',
	 'idformato'
	 
	 ];
	 
	protected $table = 'formatos_usuario';

}

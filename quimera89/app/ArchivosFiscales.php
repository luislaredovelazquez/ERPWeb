<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivosFiscales extends Model {

     protected $table = 'archivos_fiscal';

     protected $fillable = [
     'idusuario',
	 'num_certificado',
	 'logo',
	 'logoSistema',
	 'logoMime',
	 'certificado',
	 'certificadoSistema',
	 'llave',
	 'llaveSistema',
	 'contrasena' 
	 ];
	 
}

@extends('app')

@section('content')
	<link rel="stylesheet" href="{{ asset('assets/css/magnify.css') }}">
	
	@include('errors.list')  	
	
	<div class="container-fluid">
	<h4>
	Tus formatos:	
	</h4>	
		
	<div class="row" id="images1">
		
@foreach ($resultados as $resultado)
{!! Form::open(['url' => 'invoices/updateFormat']) !!}   
        <div class="col-md-3" style="margin-bottom: 20px; text-align: center;">
          <a href="{{ asset('assets/images/'.$resultado->nombre_Formato.'.jpg') }}">	
          <img  src="{{ asset('assets/images/'.$resultado->nombre_Formato.'.jpg') }}" alt="Formato" style="width: 140px; height: 160px;">
          </a>
         <br /><br />
    <!--    <form action="controllers/eliminarGaleriaController.php"  method="post" enctype="multipart/form-data">	
            <input type="submit" name="boton" value="Eliminar" id="boton" class="btn btn-danger"/> 
		    </form> -->
		
		
		<div class="form-group">
		@if($formatoSeleccionado == $resultado->id)	
		{!! Form::label('formato1',$resultado->nombre_Formato) !!}	
		{!! Form::radio('formato1', $resultado->id,true,['disabled' => 'disabled']) !!}
		@else
		{!! Form::label('formato1',$resultado->nombre_Formato) !!}	
		{!! Form::radio('formato1', $resultado->id,false,['disabled' => 'disabled']) !!}		
		@endif
		{!! Form::hidden('formato',$resultado->id,['class' => 'form-control']) !!}
		</div>
		
		<div class="form-group">
		{!! Form::submit("Seleccionar",['class' => 'btn btn-primary form-control']) !!}
		</div>
		
		   
		    
</div>
{!! Form::close() !!}
@endforeach



@foreach ($resultados2 as $resultado2)  
{!! Form::open(['url' => 'invoices/updateFormat']) !!}   
        <div class="col-md-3" style="margin-bottom: 20px; text-align: center;">
          <a href="{{ asset('assets/images/'.$resultado2->nombre_Formato.'.jpg') }}">	
          <img  src="{{ asset('assets/images/'.$resultado2->nombre_Formato.'.jpg') }}" alt="Formato" style="width: 140px; height: 160px;">
          </a>
         <br /><br />
    <!--    <form action="controllers/eliminarGaleriaController.php"  method="post" enctype="multipart/form-data">	
            <input type="submit" name="boton" value="Eliminar" id="boton" class="btn btn-danger"/> 
		    </form> -->
		
		
		<div class="form-group">
		@if($formatoSeleccionado == $resultado2->id)	
		{!! Form::label('formato2',$resultado2->nombre_Formato) !!}	
		{!! Form::radio('formato2', $resultado2->id,true,['disabled' => 'disabled']) !!}
		@else
		{!! Form::label('formato2',$resultado2->nombre_Formato) !!}	
		{!! Form::radio('formato2', $resultado2->id,false,['disabled' => 'disabled']) !!}		
		@endif
		{!! Form::hidden('formato',$resultado2->id,['class' => 'form-control']) !!}
		</div>
		
		<div class="form-group">
		{!! Form::submit("Seleccionar",['class' => 'btn btn-primary form-control']) !!}
		</div>
		
		   
		    
</div>
{!! Form::close() !!}
@endforeach

 
</div>

<h4>
¡Compra un formato!	
</h4>

<?php $i=0; ?>

	<div class="row">
	
@foreach ($resultados3 as $resultado3)   
{!! Form::open(['url' => 'shop/buyFormat']) !!}	
<?php $i = $i + 1; ?>

        <div class="col-md-3" style="margin-bottom: 20px; text-align: center;" id="div<?php echo $i; ?>">
        <div name="images">	
          <a href="{{ asset('assets/images/'.$resultado3->nombre_Formato.'.jpg') }}">	
          <img  src="{{ asset('assets/images/'.$resultado3->nombre_Formato.'.jpg') }}" alt="Formato" style="width: 140px; height: 160px;">
          </a>
        </div>  
         <br />
    <!--    <form action="controllers/eliminarGaleriaController.php"  method="post" enctype="multipart/form-data">	
            <input type="submit" name="boton" value="Eliminar" id="boton" class="btn btn-danger"/> 
		    </form> -->
		
		
		<div class="form-group">
		<b>
		{!! $resultado3->nombre_Formato !!}
		</b>	
		{!! Form::hidden('formato',$resultado3->id,['class' => 'form-control']) !!}		
		</div>
		
		<div class="form-group">
		{{ number_format($resultado3->importe * 1.16,2,'.',',') }}	
		</div>
		<br />
		
		<div class="form-group">
		<a href="#div<?php echo $i; ?>" onclick="toggle(<?php echo $i;?>);">Comprar</a>	
		</div>
		
		
		<div style="display: none;" id="compra<?php echo $i;?>" name="compra">
		<div class="form-group">
		{!! Form::label('pwd','Contraseña') !!}
		{!! Form::password('pwd',null,['class' => 'form-control']) !!}	
		</div>	
		
		<div class="form-group">
		{!! Form::submit("Comprar",['class' => 'btn btn-primary form-control']) !!}
		</div>
		</div>
		   
		    
</div>
{!! Form::close() !!} 
@endforeach


</div>

         <div class="row" style="text-align: center;">
         <?php echo $resultados3->render(); ?>
         </div>

</div>

@stop
@section('js')
 <script src="{{ asset('assets/js/magnify.js') }}"></script>
 
 <script>
 	
 $(document).ready(function() {
  $('#images1').magnificPopup({
  delegate: 'a', 
  type: 'image',
  gallery:{
    enabled:true
  }
});

  $('[name=images]').magnificPopup({
  delegate: 'a', 
  type: 'image',
  gallery:{
    enabled:true
  }
});

});

function toggle(indice)
{
	
	var divs = document.getElementsByName("compra");
	
	var nombre = "compra"+indice;
	
	for(var i = 0; i < divs.length; i = i + 1) {
		
		if(divs[i].id != nombre)		
        divs[i].style.display="none";
    }
	
	
	
	if(document.getElementById(nombre).style.display == "none")
	document.getElementById(nombre).style.display = "inline-block";
	else
	document.getElementById(nombre).style.display = "none";
	return false;
}
 	
 </script>    
 @stop
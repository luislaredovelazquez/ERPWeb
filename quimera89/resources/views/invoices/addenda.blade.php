@extends('app')

@section('content')

<h3>Crea una nueva addenda</h3>

<hr />

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Crear</div>
				<div class="panel-body">

@include('errors.list')

{!! Form::open(['url' => 'invoices/storeaddenda']) !!}


<h3>Addendas</h3>
<div class="alert alert-success" role="alert" id="addenda_success" style="display: none;"><b>Addenda agregada!</b></div>

<div class="panel-group" id="accordion3">



  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion3" href="#collapse5">
        Agregar addenda</a>
      </h4>
    </div>
    <div id="collapse5" class="panel-collapse collapse">

<div class="form-group">	
{!! Form::label('num_addenda','Addenda') !!}
{!! Form::select('num_addenda',$adds,null,['class' => 'form-control']) !!}                          
</div>

{!! Form::button("Agregar",['class' => 'btn btn-info form-control', 'id' => 'agregarAddenda' ]) !!}

   </div>
  </div>


 
<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion3" href="#collapse6">
        Tu addenda</a>
      </h4>
    </div>
    <div id="collapse6" class="panel-collapse collapse">	
  </div>

</div>

{!! Form::hidden('addenda','',['id' => 'addenda' ]) !!}
{!! Form::hidden('cfdi',$cfdi,['id' => 'cfdi' ]) !!}  
<br />
<div class="form-group">
{!! Form::submit('Enviar',['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}

</div>
</div>
</div>
</div>
</div>

@stop

@section('js')

<script type="text/javascript">
 $('#agregarAddenda').click(function(){  
      $.ajax({
      url: '/invoices/addAddenda',
      type: "post",
      data: {'num_addenda':$('#num_addenda').val(), '_token': $('input[name=_token]').val()},
      success: function(data){
      	$( "#collapse6" ).append(data);
      }
    }); 
   
   $('#collapse5').collapse('toggle');
   $('#collapse6').collapse('toggle');   
     
  }); 
  
    $('#num_addenda').change(function(){     	         
    $('#addenda').val('');
    $('#addenda_success').hide();
    $( "#collapse6" ).empty();    
  });  

</script>

@stop	
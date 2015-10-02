@extends('app')

@section('title')
	Crear Orden de Trabajo
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Crear Orden de Trabajo
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{!! Form::open(['route' => 'workOrder.store', 'method' => 'POST', 'name' => 'create-work-order']) !!}
				
				    @include ('workOrders.partials.create-edit-form')
                    
                    <div class="col-md-8 col-md-offset-2 form-group">
                        <button type="submit" class="btn btn-primary">
    						<span class="glyphicon glyphicon-floppy-disk"></span>
    						Crear
    					</button>
                    </div>
				    
				{!! Form::close() !!}

			</div>
		</div>
	</div>
@endsection

@section('script')
    
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    
    <script type="text/javascript">
    
        $('#internal_accompanists').select2({
            placeholder: "Selecciona uno o mas trabajadores...",
            allowClear: true,
        });
        
        $("#external_accompanists").select2({
            placeholder: "Digita el nombre de los acompañantes...",
            tags: true,
            allowClear: true,
        });
    
    </script>

@endsection

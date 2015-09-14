@extends('app')

@section('title')
    Crear Empleado
@stop

@section('content')
    
    <div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
		    
			<div class="panel-heading">
        		<h1>
        			<a class="link-black" href="{{route('employee.index')}}">Empleados</a>
        		</h1>
        	</div>
        	
            <div class="panel-body">
                
                <div class="form-group col-md-8 col-md-offset-2">
                    <h2>Crear Empleado</h2>
                </div>
                
                @include ('layout.notifications')
    
    			{!! Form::open(['route' => 'employee.store', 'method' => 'POST']) !!}
    			
    			@include ('employees.partials.create-edit-form')
                
                <div class="form-group col-md-8 col-md-offset-2">
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

@stop()
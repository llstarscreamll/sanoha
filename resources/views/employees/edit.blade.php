@extends('app')

@section('title')
    Actualizar Empleado
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
                    <h2>Actualizar Empleado</h2>
                </div>
                
                @include ('layout.notifications')
				
				{!! Form::model($employee, ['route' => ['employee.update', $employee->id], 'method' => 'PUT']) !!}
				
				@include ('employees.partials.create-edit-form')
                
                <div class="form-group col-md-8 col-md-offset-2">
				    <button type="submit" class="btn btn-warning">
						<span class="glyphicon glyphicon-pencil"></span>
						Actulizar
					</button>
			    </div>
				
				{!! Form::close() !!}
				
            </div>
        </div>
    </div>
    
@endsection

@section('script')

@stop()
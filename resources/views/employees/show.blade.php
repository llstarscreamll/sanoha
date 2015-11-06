@extends('app')

@section('title')
    Detalles de Empleado
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

                @include ('layout.notifications')
                <div class="form-group col-md-8 col-md-offset-2">
                    <h2>Detalles de Empleado</h2>
                </div>
                
				<div class="form-group col-md-4 col-md-offset-2">
    		        {!! Form::label('', 'Nombre') !!}
                    {!! Form::text('', $employee->name, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    
    		    <div class="form-group col-md-4">
    		        {!! Form::label('', 'Apellido') !!}
                    {!! Form::text('', $employee->lastname, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-4 col-md-offset-2">
    		        {!! Form::label('', 'No. Identificación') !!}
                    {!! Form::text('', $employee->identification_number, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-4">
    		        {!! Form::label('', 'Código') !!}
                    {!! Form::text('', $employee->internal_code, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-4 col-md-offset-2">
    		        {!! Form::label('', 'Email') !!}
                    {!! Form::text('', $employee->email, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-4">
    		        {!! Form::label('', 'Centro de Costo') !!}
                    {!! Form::text('', $employee->subCostCenter->nameWithCostCenterName, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-4 col-md-offset-2">
    		        {!! Form::label('', 'Cargo') !!}
                    {!! Form::text('', $employee->position->name, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
                <div class="form-group col-md-4">
                    {!! Form::label('authorized_to_drive_vehicles', 'Autorizado para Manejo de Vehículos') !!}
                    {!! Form::input('text', 'authorized_to_drive_vehicles', $employee->authorized_to_drive_vehicles ? 'Si' : 'No', ['class' => 'form-control', 'disabled', 'readonly']) !!}
                </div>

                <div class="col-md-10 col-md-offset-2">
                    
                    <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning">
                        <span class="glyphicon glyphicon-pencil"></span>
                        Editar
                    </a>
                    
                    {{-- This button triggers the confirmation modal window --}}
                    <button class="btn btn-danger" data-toggle="modal" data-target="#modal_confirm">
                        <span class="glyphicon glyphicon-trash"></span>
                        Mover a Papelera
                    </button>
				</div>
            </div>
        </div>
    </div>
    
    {{-- Modal Window --}}
    <div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Está Seguro?</h4>
            </div>
            <div class="modal-body">
                Toda la información de <strong>{{$employee->fullname}}</strong> será movida a la papelera!!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                {!! Form::open(['route' => ['employee.destroy', $employee->id], 'method' => 'DELETE', 'class' => 'display-inline']) !!}
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                {!! Form::close() !!}
            </div>
          </div>
        </div>
    </div>
    
@endsection

@section('scripts')

@stop()
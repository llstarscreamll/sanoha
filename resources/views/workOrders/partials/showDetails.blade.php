<div class="margin-top-20">
					        
    <div class="col-md-5 col-md-offset-1 form-group">
        {!! Form::label('', 'Autorizado Por') !!}
        {!! Form::text('', $workOrder->user->fullname, ['class' => 'form-control', 'disabled']) !!}
    </div>
    
    <div class="col-md-5 form-group">
        {!! Form::label('', 'Vehículo') !!}
        {!! Form::text('', $workOrder->vehicle->plate, ['class' => 'form-control', 'disabled']) !!}
    </div>
    
    <div class="col-md-5 col-md-offset-1 form-group">
        {!! Form::label('', 'Responsable Vehículo') !!}
        {!! Form::text('', $workOrder->employee->fullname, ['class' => 'form-control', 'disabled']) !!}
    </div>
    
    <div class="col-md-5 form-group">
        {!! Form::label('', 'Destino') !!}
        {!! Form::text('', $workOrder->destination, ['class' => 'form-control', 'disabled']) !!}
    </div>
    
    <div class="col-md-10 col-md-offset-1 form-group">
        {!! Form::label('', 'Descripción del Trabajo') !!}
        {!! Form::textarea('', $workOrder->work_description, ['class' => 'form-control', 'rows' => 4, 'disabled']) !!}
    </div>
    
    <div class="col-md-10 col-md-offset-1 form-group">
        <a href="{{route('workOrder.edit', $workOrder->id)}}" class="btn btn-warning">
        	<span class="glyphicon glyphicon-pencil"></span>
			Editar Orden de Trabajo
        </a>
    </div>
    
</div>
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
        
        <button
            class="btn btn-danger"
            data-method="DELETE"
            data-action="{{route('workOrder.destroy', $workOrder->id)}}"
            data-message="La orden de trabajo será movida a la papelera y su información ya no estará disponible..."
            data-toggle="modal"
			data-target="#confirm-modal"
        >
            <span class="glyphicon glyphicon-trash"></span>
            <span></span>Mover a Papelera</span>
        </button>
    </div>
    
</div>
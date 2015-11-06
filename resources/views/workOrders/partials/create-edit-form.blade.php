<div class="col-md-4 col-md-offset-2 form-group">
    {!! Form::label('authorized_by', 'Autorizado por') !!}
    {!! Form::text('authorized_by', \Auth::getUser()->fullname, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
    
    @if ($errors->has('authorized_by'))
        <div class="text-danger">
            {!! $errors->first('authorized_by') !!}
        </div>
    @endif
</div>

<div class="col-md-4 form-group">
    {!! Form::label('vehicle_responsable', 'Responsable de Vehículo') !!}
    {!! Form::select('vehicle_responsable', ['' => 'Selecciona al trabajador']+$vehicle_responsibles, null, ['data-live-search' => 'true', 'class' => 'form-control selectpicker show-tick', 'id' => 'vehicle_responsable']) !!}
    
    @if ($errors->has('vehicle_responsable'))
        <div class="text-danger">
            {!! $errors->first('vehicle_responsable') !!}
        </div>
    @endif
</div>

<div class="clearfix"></div>

<div class="col-md-4 col-md-offset-2 form-group">
    {!! Form::label('vehicle_id', 'Vehículo') !!}
    {!! Form::select('vehicle_id', ['' => 'Selecciona el vehículo']+$vehicles, null, ['data-live-search' => 'true', 'class' => 'form-control selectpicker show-tick', 'id' => 'vehicle_id']) !!}
    
    @if ($errors->has('vehicle_id'))
        <div class="text-danger">
            {!! $errors->first('vehicle_id') !!}
        </div>
    @endif
</div>

<div class="col-md-4 form-group">
    {!! Form::label('destination', 'Destino') !!}
    {!! Form::text('destination', null, ['class' => 'form-control']) !!}
    
    @if ($errors->has('destination'))
        <div class="text-danger">
            {!! $errors->first('destination') !!}
        </div>
    @endif
</div>

<div class="clearfix"></div>

<div class="col-md-8 col-md-offset-2 form-group">
    {!! Form::label('internal_accompanists', 'Acompañantes Internos') !!}
    {!! Form::select('internal_accompanists[]', $employees, isset($workOrder) ? $workOrder->internalAccompanists()->lists('id', 'id') : null, ['style' => 'width: 100%', 'class' => 'form-control', 'id' => 'internal_accompanists', 'multiple' => 'multiple']) !!}
    
    @if ($errors->has('internal_accompanists'))
        <div class="text-danger">
            {!! $errors->first('internal_accompanists') !!}
        </div>
    @endif
</div>

<div class="clearfix"></div>

<div class="col-md-8 col-md-offset-2 form-group">
    {!! Form::label('external_accompanists', 'Acompañantes Externos') !!}
    {!! Form::select('external_accompanists[]',
        isset($workOrder) ? $workOrder->externalAccompanists()->lists('fullname', 'fullname') : [],
        isset($workOrder) ? $workOrder->externalAccompanists()->lists('fullname', 'fullname') : null,
        ['style' => 'width: 100%', 'class' => 'form-control', 'id' => 'external_accompanists', 'multiple'])
    !!}
    
    @if ($errors->has('external_accompanists'))
        <div class="text-danger">
            {!! $errors->first('external_accompanists') !!}
        </div>
    @endif
</div>

<div class="col-md-8 col-md-offset-2 form-group">
    {!! Form::label('work_description', 'Descripción del Trabajo') !!}
    {!! Form::textarea('work_description', null, ['class' => 'form-control', 'rows' => 4]) !!}
    
    @if ($errors->has('work_description'))
        <div class="text-danger">
            {!! $errors->first('work_description') !!}
        </div>
    @endif
</div>
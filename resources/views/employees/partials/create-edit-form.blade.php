<div class="form-group col-md-4 col-md-offset-2">
	{!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('name'))
        <div class="text-danger">
            {!! $errors->first('name') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-4">
	{!! Form::label('lastname', 'Apellido') !!}
    {!! Form::text('lastname', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('lastname'))
        <div class="text-danger">
            {!! $errors->first('lastname') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-4 col-md-offset-2">
	{!! Form::label('identification_number', 'No. Identificación') !!}
    {!! Form::text('identification_number', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('identification_number'))
        <div class="text-danger">
            {!! $errors->first('identification_number') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-4">
	{!! Form::label('email', 'Correo Electrónico') !!}
    {!! Form::text('email', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('email'))
        <div class="text-danger">
            {!! $errors->first('email') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-4 col-md-offset-2">
	{!! Form::label('phone', 'Teléfono') !!}
    {!! Form::text('phone', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('phone'))
        <div class="text-danger">
            {!! $errors->first('phone') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-4">
	{!! Form::label('sub_cost_center_id', 'Centro de Costo') !!}
    {!! Form::select('sub_cost_center_id', ['' => 'Selecciona centro de costo']+$cost_centers, null, ['class' => 'form-control selectpicker show-tick', 'data-live-search' => 'true'])  !!}
    @if ($errors->has('sub_cost_center_id'))
        <div class="text-danger">
            {!! $errors->first('sub_cost_center_id') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-4 col-md-offset-2">
	{!! Form::label('position_id', 'Cargo') !!}
    {!! Form::select('position_id', ['' => 'Selecciona cargo']+$positions, null, ['class' => 'form-control selectpicker show-tick', 'data-live-search' => 'true'])  !!}
    @if ($errors->has('position_id'))
        <div class="text-danger">
            {!! $errors->first('position_id') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-6">
    {!! Form::label('authorized_to_drive_vehicles', 'Autorizado para Manejo de Vehículos') !!}
    <div>
        {!! Form::checkbox(
            'authorized_to_drive_vehicles',
            '1',
            null,
            [
                'class' => 'bootstrap_switch',
                'data-size' => 'small',
                'data-on-text' => 'SI',
                'data-off-text' => 'NO',
            ])
        !!}
    </div>
    @if ($errors->has('authorized_to_drive_vehicles'))
        <div class="text-danger">
            {!! $errors->first('authorized_to_drive_vehicles') !!}
        </div>
    @endif
</div>
<fieldset>
    <legend>Detalles</legend>

    <div class="row">

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('name', 'Nombres') !!}
            {!! Form::input('text', 'name', null, ['class' => 'form-control', 'required' => "required"]) !!}
            @if ($errors->has('name'))
                <div class="text-danger">
                    {!! $errors->first('name') !!}
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('lastname', 'Apellidos') !!}
            {!! Form::input('text', 'lastname', null, ['class' => 'form-control']) !!}
            @if ($errors->has('lastname'))
                <div class="text-danger">
                    {!! $errors->first('lastname') !!}
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('email', 'Correo Electrónico') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'required' => "required"]) !!}
            @if ($errors->has('email'))
                <div class="text-danger">
                    {!! $errors->first('email') !!}
                </div>
            @endif
        </div>
    </div>
    </div>
</fieldset>

<fieldset>
    <legend>Autorización</legend>

    <div class="row">
        
    <div class="col-md-5">
        <div class="form-group">
            {!! Form::label('role_id', 'Tipo de Usuario') !!}
            {!! Form::select('role_id[]', $roles, isset($user) ? $user->getIdRoles() : null, ['id' => 'role_id', 'multiple' => true, 'class' => 'form-control selectpicker', 'title' => 'Elige el tipo de usuario']) !!}
            @if ($errors->has('role_id'))
                <div class="text-danger">
                    {!! $errors->first('role_id') !!}
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            {!! Form::label('costCenter_id', 'Centro de Costo') !!}
            
            {{--
            
            {!! Form::select(
                'costCenter_id[]',
                $subCostCenters,
                isset($user) ? $user->getSubCostCentersId() : null,
                [
                    'id' => 'costCenter_id',
                    'multiple' => true,
                    'class' => 'form-control selectpicker',
                    'title' => 'Elige Centro de Costos'
                ]
            ) !!}
            --}}
            
            <select name="subCostCenter_id[]" id="subCostCenter_id" multiple class="form-control selectpicker"  title="Elige Centro de Costos">

                @foreach($costCenters as $costCenter)
                
                    <optgroup label="{{ $costCenter->name }}">
                        
                        @foreach($costCenter->subCostCenter as $subCostCenter)
                        
                            <option
                                value="{{$subCostCenter->id}}"
                                    {{ isset($userSubCostCenters)
                                        ? (in_array($subCostCenter->id, $userSubCostCenters)
                                            ? 'selected'
                                            : ''
                                        )
                                        : ''
                                    }}
                            >
                                    
                                {{$subCostCenter->name}}
                                    
                            </option>
                            
                        @endforeach
                            
                    </optgroup>
                
                @endforeach
                
            </select>
            
            @if ($errors->has('costCenter_id'))
                <div class="text-danger">
                    {!! $errors->first('costCenter_id') !!}
                </div>
            @endif
        </div>
    </div>
    
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('activated', 'Activado') !!}
            <div>
                {!! Form::checkbox(
                    'activated',
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
            @if ($errors->has('activated'))
                <div class="text-danger">
                    {!! $errors->first('activated') !!}
                </div>
            @endif
        </div>
    </div>
    </div>

</fieldset>

<fieldset>
    <legend>Autenticación</legend>
    
    <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('password', 'Contraseña') !!}
            {!! Form::password('password', ['class' => 'form-control', $password_required]) !!}
            @if ($errors->has('password'))
                <div class="text-danger">
                    {!! $errors->first('password') !!}
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('password_repeat', 'Confirmar Contraseña') !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control', $password_required]) !!}
            @if ($errors->has('password'))
                <div class="text-danger">
                    {!! $errors->first('password') !!}
                </div>
            @endif
        </div>
    </div>
    </div>
</fieldset>
<div class="col-md-10 col-md-offset-1">

    <fieldset>
        <legend>Detalles</legend>
    
        <div class="row">
    
            <div class="form-group col-md-4">
                {!! Form::label('name', 'Nombres') !!}
                {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
                @if ($errors->has('name'))
                    <div class="text-danger">
                        {!! $errors->first('name') !!}
                    </div>
                @endif
            </div>
    
            <div class="form-group col-md-4">
                {!! Form::label('lastname', 'Apellidos') !!}
                {!! Form::input('text', 'lastname', null, ['class' => 'form-control']) !!}
                @if ($errors->has('lastname'))
                    <div class="text-danger">
                        {!! $errors->first('lastname') !!}
                    </div>
                @endif
            </div>
    
            <div class="form-group col-md-4">
                {!! Form::label('email', 'Correo Electrónico') !!}
                {!! Form::email('email', null, ['class' => 'form-control']) !!}
                @if ($errors->has('email'))
                    <div class="text-danger">
                        {!! $errors->first('email') !!}
                    </div>
                @endif
            </div>
    
        </div>
        
    </fieldset>
    
    <fieldset>
        <legend>Autorización</legend>
    
        <div class="row">
            
            <div class="form-group col-md-6">
                {!! Form::label('role_id', 'Tipo de Usuario') !!}
                {!! Form::select('role_id[]', $roles, isset($user) ? $user->getIdRoles() : null, ['id' => 'role_id', 'multiple' => true, 'class' => 'form-control selectpicker', 'title' => 'Elige el tipo de usuario']) !!}
                @if ($errors->has('role_id'))
                    <div class="text-danger">
                        {!! $errors->first('role_id') !!}
                    </div>
                @endif
            </div>
    
            <div class="col-md-6 form-group">
                {!! Form::label('sub_cost_center_id', 'Centro de Costo') !!}
                <select name="sub_cost_center_id[]" id="sub_cost_center_id" multiple class="form-control selectpicker"  title="Elige Centro de Costos">
        
                    @foreach($costCenters as $costCenter)
                    
                        <optgroup label="{{ $costCenter->name }}">
                            
                            @foreach($costCenter->subCostCenter as $subCostCenter)
                            
                                <option value="{{$subCostCenter->id}}" {{!isset($userSubCostCenters) ?: !in_array($subCostCenter->id, $userSubCostCenters) ?: 'selected'}}>
                                        
                                    {{$subCostCenter->name}}
                                        
                                </option>
                                
                            @endforeach
                                
                        </optgroup>
                    
                    @endforeach
                    
                </select>
                
                @if ($errors->has('sub_cost_center_id'))
                    <div class="text-danger">
                        {!! $errors->first('sub_cost_center_id') !!}
                    </div>
                @endif
            </div>
            
            <div class="clearfix"></div>
            
            <div class="col-md-6 form-group">
                {!! Form::label('area_id', 'Área') !!}
                {!! Form::select('area_id', $areas, null, ['id' => 'area_id', 'class' => 'form-control selectpicker', 'title' => 'Elige el área']) !!}
                @if ($errors->has('area_id'))
                    <div class="text-danger">
                        {!! $errors->first('area_id') !!}
                    </div>
                @endif
            </div>
    
            <div class="form-group col-md-6">
                {!! Form::label('area_chief', 'Es jefe de Área?') !!}
                <div>
                    {!! Form::checkbox(
                        'area_chief',
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
                @if ($errors->has('area_chief'))
                    <div class="text-danger">
                        {!! $errors->first('area_chief') !!}
                    </div>
                @endif
            </div>
            
            <div class="clearfix"></div>
        
            <div class="col-md-6 form-group">
                {!! Form::label('employee_id', 'Empleados Asignados') !!}
                {!! Form::select('employee_id[]', $employees, isset($user) ? $user->getIdEmployees() : null, ['id' => 'employee_id', 'multiple' => true, 'class' => 'form-control selectpicker', 'title' => 'Elige el empleado']) !!}
                @if ($errors->has('employee_id'))
                    <div class="text-danger">
                        {!! $errors->first('employee_id') !!}
                    </div>
                @endif
            </div>
    
            <div class="form-group col-md-6">
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
    
    </fieldset>
    
    <fieldset>
        <legend>Autenticación</legend>
        
        <div class="row">
            
            <div class="form-group col-md-6">
                {!! Form::label('password', 'Contraseña') !!}
                {!! Form::password('password', ['class' => 'form-control', $password_required]) !!}
                @if ($errors->has('password'))
                    <div class="text-danger">
                        {!! $errors->first('password') !!}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-6">
                {!! Form::label('password_repeat', 'Confirmar Contraseña') !!}
                {!! Form::password('password_confirmation', ['class' => 'form-control', $password_required]) !!}
                @if ($errors->has('password'))
                    <div class="text-danger">
                        {!! $errors->first('password') !!}
                    </div>
                @endif
            </div>

        </div>
        
    </fieldset>

</div>
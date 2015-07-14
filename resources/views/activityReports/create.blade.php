@extends('app')

@section('title')
	Registrar Labor Minera
@stop

@section('content')
	
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Registrar Labor Minera
				</h1>
				
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
                {!! Form::open(['route' => (empty($employee) ? 'activityReport.create' : 'activityReport.store'), 'method' => empty($employee) ? 'GET' : 'POST']) !!}

                    <fieldset>
                        <legend>
                            Detalles de Labor
                        </legend>
                        
                        <div class="col-md-6 col-md-offset-3">
                            <div class="form-group">
                                {!! Form::label('employee_id', 'Trabajador') !!}
                                {!! Form::select('employee_id', ['' => 'Selecciona un trabajador']+$employees, $parameters['employee_id'], ['id' => 'employee_id', 'class' => 'form-control selectpicker show-tick', 'title' => 'Elige al trabajador']) !!}
                                
                                @if ($errors->has('employee_id'))
                                <div class="text-danger">
                                    {!! $errors->first('employee_id') !!}
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if(!empty($employee))
                        
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group">
                                    {!! Form::label('mining_activity_id', 'Labor Minera') !!}
                                    <select name="mining_activity_id" id="mining_activity_id" class="form-control selectpicker show-tick">
                                        
                                        <option value="" data-maximum="" {{ isset($input['mining_activity_id']) ? '' : 'selected' }}>Selecciona una actividad</option>
                                        
                                        @foreach($miningActivities as $activity)
                                            
                                            <option value="{{$activity->id}}" data-maximum="{{$activity->maximum}}" {{ (isset($input['mining_activity_id']) && $input['mining_activity_id'] === $activity->id) ? 'selected' : '' }}>
                                                {{$activity->nameAndAbbreviation}}
                                            </option>
                                        
                                        @endforeach
                                        
                                    </select>
                                    
                                    @if ($errors->has('mining_activity_id'))
                                    <div class="text-danger">
                                        {!! $errors->first('mining_activity_id') !!}
                                    </div>
                                    @endif
                                </div>
                                
                            </div>
                            
                            <div class="col-md-3 col-md-offset-3">
                                <div class="form-group">
                                    {!! Form::label('quantity', 'Cantidad') !!}
                                    {!! Form::number('quantity', null, ['class' => 'form-control', 'id' => 'quantity', 'min' => '1']) !!}
                                    @if ($errors->has('quantity'))
                                    <div class="text-danger">
                                        {!! $errors->first('quantity') !!}
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if(\Auth::getUser()->can('activityReport.assignCosts'))
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('price', 'Precio') !!}
                                    {!! Form::number('price', null, ['class' => 'form-control', 'step' => '100']) !!}
                                    
                                    @if ($errors->has('price'))
                                    <div class="text-danger">
                                        {!! $errors->first('price') !!}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group">
                                    {!! Form::label('comment', 'Comentario') !!}
                                    {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => '3']) !!}
                                    
                                    @if ($errors->has('comment'))
                                    <div class="text-danger">
                                        {!! $errors->first('comment') !!}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-floppy-disk"></span>
                                    Registrar
                                </button>
                            </div>
                        
                        @endif
                        
                    
                    </fieldset>
                    
                    {{-- ***************************************
                        Vista previa se los cambios realizados
                    ********************************************--}}
                    
                    <fieldset class="margin-top-20">
                        
                        <legend>
                            Vista Previa de Actividades
                        </legend>
                        
                        @if(!empty($employee))
                        
                        <div class="table-responsive">

                            <table class="table table-hover table-bordered table-vertical-align-middle">
                                
                                <thead>
                                    
                                    <tr>
                                        <th>Nombre</th>
                                        
                                        @foreach($miningActivities as $activity)
                                        
                                            <th>
                                                <span title="{{$activity->name}}" data-toggle="tooltip">
                                                    {{$activity->short_name}}
                                                </span>
                                            </th>
                                        
                                        @endforeach
                                        
                                    </tr>
                                    
                                </thead>
                                
                                <tbody>
                                    
                                    {{-- No print the reported_by data --}}
                                    
                                    @if(!empty($employee_activities))
                                    
                            			@foreach($employee_activities as $key => $activity)
                            
                            				@if($key !== 'reported_by')
                            				<tr class="{{ $key === 'totals' ? 'text-bold' : '' }}">
                            					@foreach($activity as $k => $v)
                            						<td class="{{ $k !== 'employee_fullname' ? 'text-center' : '' }} ">
                            							{{ $v }}
                            						</td>
                            					@endforeach
                            				</tr>
                            				@endif
                            
                            			@endforeach
                        			
                        			@else
                        			
                            			<tr>
                            			    <td>{{$employee->fullname}}</td>
                            			    <td colspan={{count($miningActivities)}}>
                            			        
                            			        <div class="alert alert-warning margin-bottom-0">
                            			            No hay actividades registradas...
                            			        </div>
                            			        
                            			    </td>
                            			</tr>
                            			
                        			@endif
                                    
                                </tbody>
                                
                            </table>
                        
                        </div>
                        @else
                            
                            <div class="alert alert-warning">
                                Selecciona un trabajador...
                            </div>
                            
                        @endif
                        
                        
                    </fieldset>
                
                {!! Form::close() !!}
                    
			</div>
		</div>
	</div>
	
@endsection

@section('script')

    <script type="text/javascript">
        
        $(document).ready(function(){
            var option = $('#mining_activity_id option:selected').attr('data-maximum');
            if(option !== ''){
                $('#quantity').attr('max', option).val('1');
            }
        });
        
        $("#employee_id").change(function(){
            $(this).closest('form').attr('action', '{{route('activityReport.create')}}');
            $(this).closest('form').attr('method', 'GET');
            $(this).closest('form').submit();
        });
        
        $('#mining_activity_id').change(function(){
            var option = $('option:selected', this).attr('data-maximum');
            $('#quantity').attr('max', option).val('1');
            console.log('El máximo del campo cantidad es = ' + $('#quantity').attr('max'));
        });
        
        {{-- Initialize all tooltips --}}
        $('[data-toggle="tooltip"]').tooltip();
        
    </script>

@endsection

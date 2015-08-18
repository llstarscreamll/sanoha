@extends('app')

@section('title')
	Detalle de Labor Minera
@stop

@section('content')

    <div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Detalle de Labor Minera <small>{{$parameters['costCenter_name']}}</small>
				</h1>
				
			</div>
            
            <div class="panel-body">
				
				@include ('layout.notifications')
				
                <form>

                    <fieldset>
                        <legend>
                            Detalles de Labor <span data-toggle="tooltip" data-placement="top" title="Fecha de Reporte" class="small-date">{{ $activity->reported_at->format('l j \\of F Y') }}</span>
                        </legend>
                        
                        <div class="col-md-6 col-md-offset-3">
                            <div class="form-group">
                                {!! Form::label('employee_id', 'Trabajador') !!}

                                <select name="employee_id" id="employee_id" class="form-control selectpicker show-tick" disabled>
                                    
                                    <option selected>{{ $activity->employee->fullname }}</option>
                                    
                                </select>
                            </div>
                        </div>


                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group">
                                    {!! Form::label('mining_activity_id', 'Labor Minera') !!}
                                    <select name="mining_activity_id" id="mining_activity_id" class="form-control selectpicker show-tick" disabled>
                                        
                                        <option selected>{{ $activity->miningActivity->name }}</option>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-md-offset-3">
                                <div class="form-group">
                                    {!! Form::label('quantity', 'Cantidad') !!}
                                    {!! Form::number('quantity', $activity->quantity, ['class' => 'form-control', 'id' => 'quantity', 'disabled', 'readonly']) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('price', 'Precio') !!}
                                    {!! Form::number('price', $activity->price, ['class' => 'form-control', 'disabled', 'readonly']) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group">
                                <label for="reported_at">Fecha de Actividad</label>
                                <div class="input-group">
                                    {!! Form::text('reported_at', $activity->reported_at, ['class' => 'form-control', 'id' => 'reported_at', 'placeholder' => 'Elegir Fecha', 'readonly']) !!}
                                </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group">
                                    {!! Form::label('comment', 'Comentario') !!}
                                    {!! Form::textarea('comment', $activity->comment, ['class' => 'form-control', 'rows' => '3', 'disabled', 'readonly']) !!}
                                </div>
                            </div>
                    
                    </fieldset>
                    
                </form>
                
                <div class="col-md-6 col-md-offset-3">
                    <a href="{{ route('activityReport.edit', $activity->id) }}" class="btn btn-warning">
                        <span class="glyphicon glyphicon-pencil"></span>
                        Editar
                    </a>
                </div>
                    
			</div>
		</div>
	</div>
@endsection

@section('script')

    <script type="text/javascript">
    
        $(document).ready(function(){
            
            {{-- Initialize all tooltips --}}
            $('[data-toggle="tooltip"]').tooltip();
            
        });
    
    </script>

@stop
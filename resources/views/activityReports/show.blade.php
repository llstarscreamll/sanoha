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
                            
                            <div class="col-md-3 col-md-offset-3 form-group">
                                {!! Form::label('', 'Horas Trabajadas') !!}
                                {!! Form::number('', $activity->worked_hours, ['class' => 'form-control', 'disabled']) !!}
                                    
                                @if ($errors->has('worked_hours'))
                                <div class="text-danger">
                                    {!! $errors->first('worked_hours') !!}
                                </div>
                                @endif
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="reported_at">Fecha de Actividad</label>
                                {!! Form::text('reported_at', $activity->reported_at->toDateString(), ['class' => 'form-control', 'id' => 'reported_at', 'disabled']) !!}
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
              Se moverá a la papelera la información de la actividad de <strong>{{ $activity->employee->fullname }}</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                {!! Form::open(['route' => ['activityReport.destroy', $activity->id], 'method' => 'DELETE', 'class' => 'display-inline']) !!}
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                {!! Form::close() !!}
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
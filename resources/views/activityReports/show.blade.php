@extends('app')

@section('title')
	Detalle de Labor Minera
@stop

@section('content')

    <div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					<a class="link-black" href="{{route('activityReport.individual')}}">Reporte de Labores Mineras</a> <small>{{\Session::get('current_cost_center_name')}}</small>
				</h1>
				
			</div>
            
            <div class="panel-body">
				
				@include ('layout.notifications')
				
                <form>

                    <fieldset>
                        <legend class="form-group col-md-6 col-md-offset-3">
                            Detalles de Labor <span data-toggle="tooltip" data-placement="top" title="Fecha de Reporte" class="small-date">{{ $activity->reported_at->format('l j \\of F Y') }}</span>
                        </legend>
                        
                        <div class="col-md-6 col-md-offset-3">
                            <div class="form-group">
                                {!! Form::label('employee_id', 'Trabajador') !!}

                                <select class="form-control selectpicker show-tick" disabled>
                                    <option selected>{{ $activity->employee->fullname }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-md-offset-3">
                            <div class="form-group">
                                {!! Form::label('mining_activity_id', 'Labor Minera') !!}
                                <select class="form-control selectpicker show-tick" disabled>
                                    <option selected>{{ $activity->miningActivity->name }}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-md-offset-3">
                            <div class="form-group">
                                {!! Form::label('', 'Cantidad') !!}
                                {!! Form::text('', $activity->quantity, ['class' => 'form-control', 'id' => 'quantity', 'disabled', 'readonly']) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('', 'Precio') !!}
                                {!! Form::text('', $activity->price, ['class' => 'form-control', 'disabled', 'readonly']) !!}
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-md-offset-3">
                            <div class="form-group">
                            {!! Form::label('', 'Fecha de Actividad') !!}
                            {!! Form::text('', $activity->reported_at->toDateString(), ['class' => 'form-control', 'disabled']) !!}
                            </div>
                        </div>

                        <div class="col-md-6 col-md-offset-3">
                            <div class="form-group">
                                {!! Form::label('', 'Comentario') !!}
                                {!! Form::textarea('', $activity->comment, ['class' => 'form-control', 'rows' => '3', 'disabled', 'readonly']) !!}
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
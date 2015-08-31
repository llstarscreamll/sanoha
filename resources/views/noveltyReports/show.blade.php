@extends('app')

@section('title')
	Detalles de Novedad Reportada
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					<a class="link-black" href="{{route('noveltyReport.index')}}">Reportes de Novedad</a> <small>{{\Session::get('current_cost_center_name')}}</small>
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				<form>
				    <fieldset>
				        <legend class="col-md-6 col-md-offset-3">
				            Detalles de Novedad
				        </legend>
				        
        				<div class="col-md-6 col-md-offset-3 form-group">
        				    {!!Form::label('', 'Centro de Costo')!!}
        				    {!!Form::text('', \Session::get('current_cost_center_name') . ' - ' . $novelty->subCostCenter->name, ['class' => 'form-control', 'disabled'])!!}
        				</div>
        				
        				<div class="col-md-6 col-md-offset-3 form-group">
        				    {!!Form::label('', 'Trabajador')!!}
        				    {!!Form::text('', $novelty->employee->fullname, ['class' => 'form-control', 'disabled'])!!}
        				</div>
        				
        				<div class="col-md-6 col-md-offset-3 form-group">
        				    {!!Form::label('', 'Tipo de Novedad')!!}
        				    {!!Form::text('', $novelty->novelty->name, ['class' => 'form-control', 'disabled'])!!}
        				</div>
        				
        				<div class="col-md-6 col-md-offset-3 form-group">
        				    {!!Form::label('', 'Fecha de la Novedad')!!}
        				    {!!Form::text('', $novelty->reported_at->toDateString(), ['class' => 'form-control', 'disabled'])!!}
        				</div>
        				
        				<div class="col-md-6 col-md-offset-3 form-group">
        				    {!!Form::label('', 'Comentario')!!}
        				    {!!Form::textarea('', $novelty->comment, ['class' => 'form-control', 'rows' => '4', 'disabled'])!!}
        				</div>
    				
				    </fieldset>
				    
				    <div class="col-md-6 col-md-offset-3">
					    <a href="{{route('noveltyReport.edit', $novelty->id)}}" class="btn btn-warning">
					        <span class="glyphicon glyphicon-pencil"></span>
					        Editar
					    </a>
					    
					    {{-- This button triggers the confirmation modal window --}}
		                <a class="btn btn-danger" data-toggle="modal" data-target="#modal_confirm">
		                    <span class="glyphicon glyphicon-trash"></span>
		                    Mover a Papelera
		                </a>
				    </div>
                </form>
                
                {{-- Modal Window --}}
			    <div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			        <div class="modal-dialog">
			          <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			                <h4 class="modal-title" id="myModalLabel">Está Seguro?</h4>
			            </div>
			            <div class="modal-body">
			              Se moverá a la papelera la información de la novedad #<strong>{{ $novelty->id}}</strong> de <strong>{{$novelty->employee->fullname}}</strong>
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			                {!! Form::open(['route' => ['noveltyReport.destroy', $novelty->id], 'method' => 'DELETE', 'class' => 'display-inline']) !!}
			                    <button type="submit" class="btn btn-danger">Confirmar</button>
			                {!! Form::close() !!}
			            </div>
			          </div>
			        </div>
			    </div>
                
			</div>
		</div>
	</div>
@endsection

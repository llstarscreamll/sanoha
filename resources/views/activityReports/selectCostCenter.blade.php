@extends('app')

@section('title')
	Proyecto Asignados
@stop

@section('content')
	
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Proyecto Asignados
				</h1>
				
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				

              	@if(count(Auth::getUser()->costCenter) > 0)
				    
				    <ul class="list-group">
				        
					@foreach(Auth::getUser()->costCenter as $center)
						<li class="list-group-item">
						    <a href="{{ url(route('activityReport.activitiesFromCostCenter', [ $center->id ])) }}">{{ $center->name }}</a>
					    </li>
					@endforeach
				    
				    </ul>
				    
				@else
					<div class="alert alert-danger">
					    No tienes proyectos asignados
					</div>
				@endif

			</div>
		</div>
	</div>
	
@endsection
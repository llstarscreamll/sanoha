@extends('app')

@section('title')
	Reportar Actividades de la Orden de Trabajo
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Reportar Actividades de la Orden de Trabajo
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{!! Form::open(['route' => ['workOrder.internal_accompanist_report_store', $workOrder->id, $employee->id], 'method' => 'POST', 'id' => 'report-work-order-activity']) !!}
				
				    @include('workOrders.partials.wysiwygReport')
				    
				    <div class="col-xs-12 form-group">
				        <button type="submit" class="btn btn-primary">
				            <span class="glyphicon glyphicon-ok"></span>
				            Reportar
				        </button>
				    </div>
				    
				{!! Form::close() !!}

			</div>
		</div>
	</div>
@endsection

@section('script')

    <script type="text/javascript" src="{{ asset('/ckeditor/ckeditor.js') }}"></script>
    
    <script>
        
        {{-- inicializa el editor ckeditor en el textarea --}}
        CKEDITOR.replace( 'work_order_report', {
                language: 'es',
                resize_enabled : true,
                height : 350
            });
        
    </script>

@stop()
@extends('app')

@section('title')
	Editar Reporte de Actividades de Orden de Trabajo
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>Editar Reporte de Actividades de Orden de Trabajo</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{!! Form::model($mainReport, ['route' => ['workOrder.mainReportUpdate', $mainReport->work_order_id, $mainReport->id], 'method' => 'PUT', 'id' => 'edit-main-work-order-report']) !!}
				
				    @include('workOrders.partials.wysiwygReport', ['report' => $mainReport->work_order_report])
				    
				    <div class="col-xs-12 form-group">
				        <button type="submit" class="btn btn-warning">
				            <span class="glyphicon glyphicon-pencil"></span>
				            Actualizar
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
@extends('app')

@section('title')
	Editar Reporte de Acompañante Interno
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Editar Reporte de Acompañante Interno
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{!! Form::open(['route' => ['workOrder.internal_accompanist_report_store', $workOrder->id, $employee->id], 'method' => 'POST', 'id' => 'update-internal-report']) !!}
                    
				    @include('workOrders.partials.wysiwygReport', ['report' => $workOrder->internalAccompanists->first()->pivot->work_report])
				    
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
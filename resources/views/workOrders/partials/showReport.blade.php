<div id="work-order-report" class="margin-top-20">

    @if(count($reports = $workOrder->workOrderReports) > 0)
    	
    	@foreach($reports as $report)
        	<div class="panel panel-default">
        		
				<div class="panel-body">
					{!!$report->work_order_report!!}
				</div>
				
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-6">
							<a href="{{route('workOrder.mainReportEdit', [$workOrder->id, $report->id])}}" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Actualizar Reporte Principal">
								<span class="glyphicon glyphicon-pencil"></span>
								<span class="sr-only">Actualizar Reporte Principal</span>
							</a>

							<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Mover a Papelera el Reporte Principal">
								<button
									class="btn btn-danger btn-sm"
									data-action="{{route('workOrder.mainReportDestroy', $report->id)}}"
									data-method="DELETE"
									data-message="La información del reporte principal de {{$report->reportedBy->fullname}} ya no estará disponible..."
									data-toggle="modal"
									data-target="#confirm-modal"
								>
									<span class="glyphicon glyphicon-trash"></span>
									<span class="sr-only">Mover a Papelera el Reporte Principal</span>
								</button>
							</div>
						</div>
						<div class="col-md-6 text-right">
							Reportado por <strong>{{$report->reportedBy->fullname}}</strong> el <strong>{{$report->created_at}}</strong>
						</div>
					</div>
				</div>
				
			</div>
    	@endforeach
    
    @else
    
    	<div class="col-md-8 col-md-offset-2 alert alert-warning"><strong>{{$workOrder->employee->fullname}}</strong> no ha reportado actividad...</div>
    		
    @endif

    <div class="clearfix"></div>
    <div>
        <a id="btn-create-main-report" class="btn btn-primary" href="{{route('workOrder.mainReport', $workOrder->id)}}">
			<span class="glyphicon glyphicon-list-alt"></span>
			Crear Reporte
		</a>
	</div>
    
</div>
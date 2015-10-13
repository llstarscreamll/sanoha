<div id="work-order-report" class="margin-top-20">

    <div>
    @if(count($reports = $workOrder->workOrderReports) > 0)
    	
    	@foreach($reports as $report)
        	<div class="panel panel-default">
        		
				<div class="panel-body">
					{!!$report->work_order_report!!}
				</div>
				
				<div class="panel-footer text-right">
					Reportado por <strong>{{$report->reportedBy->fullname}}</strong> el <strong>{{$report->created_at}}</strong>
				</div>
				
			</div>
    	@endforeach
    
    @else
    
    	<div class="col-md-8 col-md-offset-2 alert alert-warning"><strong>{{$workOrder->employee->fullname}}</strong> no ha reportado actividad...</div>
    		
    @endif
    </div>
    <div class="clearfix"></div>
    <div>
        <a id="btn-create-main-report" class="btn btn-primary" href="{{route('workOrder.mainReport', $workOrder->id)}}">
			<span class="glyphicon glyphicon-list-alt"></span>
			Crear Reporte
		</a>
	</div>
    
</div>
<div class="margin-top-20" id="internal-accompanists">
    <div class="clearfix"></div>

    @if(count($workOrder->internalAccompanists) > 0)
    <div class="col-md-10 col-md-offset-1 panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        @foreach($workOrder->internalAccompanists as $employee)
            
        <div class="panel panel-default">
            
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$employee->id}}" aria-expanded="true" aria-controls="collapseOne">
                        {{$employee->fullname}} <small>{{$employee->position->name}}</small>
                    </a>
                    
                    <div class="pull-right">
                        <a href="{{route('workOrder.internal_accompanist_report_form', [$workOrder->id, $employee->id])}}" class="btn btn-xs btn-primary">
                            <span class="glyphicon glyphicon-list-alt"></span>
                            <span class="sr-only">Crear Reporte de Actividades Realizadas</span>
                        </a>
                    </div>
                </h4>
            </div>
            
            <div id="collapse-{{$employee->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                
                <div class="panel-body">
                    
                    @if(!is_null($employee->pivot->work_report))
                    
                        {!!$employee->pivot->work_report!!}
                        
                    @else
                    
                        <div class="col-md-10 col-md-offset-1 alert alert-warning">El trabajador no ha reportado actividad...</div>
                    
                    @endif
                    
                </div>

                <div class="panel-footer text-right">
                    
                    @if(!is_null($employee->pivot->work_report))
                        Reportado por <strong>{{$workOrder->getInternalAccompanistsReportedBy($employee->pivot->reported_by)}}</strong> el <strong>{{$employee->pivot->reported_at}}</strong>
                    @else
                        '---'
                    @endif
                    
                </div>
            </div>
        
        </div>
            
        @endforeach
    </div>
    
    @else
    
        <div class="col-md-10 col-md-offset-1 alert alert-warning">
            No se registraron acompañantes internos...
        </div>
    
    @endif
    
</div>
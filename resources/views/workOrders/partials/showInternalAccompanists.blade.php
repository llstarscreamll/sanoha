<div class="margin-top-20" id="internal-accompanists">
    <div class="clearfix"></div>

    @if(count($internal_accompanists = $workOrder->internalAccompanists) > 0)
    <div class="col-md-10 col-md-offset-1 panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        @foreach($internal_accompanists as $employee)    
            
        <div class="panel panel-default">
            
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$employee->id}}" aria-expanded="true" aria-controls="collapseOne">
                        {{$employee->fullname}} <small>{{$employee->position->name}}</small>
                    </a>
                </h4>
            </div>
            
            <div id="collapse-{{$employee->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                
                <div class="panel-body">
                    
                    @if(!is_null($employee->pivot->work_report))
                    
                        {{$employee->pivot->work_report}}
                        
                    @else
                    
                        <div class="col-md-10 col-md-offset-1 alert alert-warning">El trabajador no ha reportado actividad...</div>
                    
                    @endif
                    
                </div>
                
                <div class="panel-footer text-right">
                    {{!is_null($employee->pivot->work_report)
                        ? 'Reportado por '.$employee->pivot->reported_by.' el '.$employee->pivot->reported_at
                        : '---'
                    }}
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
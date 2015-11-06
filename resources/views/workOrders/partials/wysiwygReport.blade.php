<div class="col-xs-12 form-group">
    
    {!! Form::label('work_order_report', 'Descripción de las actividades realizadas') !!}
    {!! Form::textarea('work_order_report', isset($report) ? $report : null, ['class' => 'form-control', 'name' => 'work_order_report']) !!}
    
</div>
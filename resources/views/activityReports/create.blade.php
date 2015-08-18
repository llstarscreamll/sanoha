@extends('app')

@section('title')
	Registrar Labor Minera
@stop

@section('content')
	
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Registrar Labor Minera <small>{{$parameters['costCenter_name']}}</small>
				</h1>
				
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
                {!! Form::open(['route' => (is_null($parameters['employee_id']) ? 'activityReport.create' : 'activityReport.store'), 'method' => is_null($parameters['employee_id']) ? 'GET' : 'POST']) !!}
                    
                    <fieldset>
                        <legend>
                            Detalles de Labor
                            <span data-toggle="tooltip" data-placement="top" title="Fecha de Reporte" class="small-date">
                                {{ isset($activity) ? $activity->reported_at->format('l j \\of F Y') : \Carbon\Carbon::now()->format('l j \\of F Y') }}
                            </span>
                        </legend>
                        
                        @include('activityReports.partials.form-create', ['btn_options' => ['class' => 'btn btn-primary', 'caption' => 'Registrar', 'icon' => 'glyphicon glyphicon-floppy-disk']])
                        
                    </fieldset>
                    
                    @include('activityReports.partials.form-create-preview')
                
                {!! Form::close() !!}
                    
			</div>
		</div>
	</div>
	
@endsection

@section('script')
    
    {{-- Include Date Range Picker --}}
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker-bs3.css" />

    <script type="text/javascript">
        
        $(document).ready(function(){
            var option = $('#mining_activity_id option:selected').attr('data-maximum');
            if(option !== ''){
                $('#quantity').attr('max', option).val('1');
            }
        });
        
        $("#employee_id").change(function(){
            $(this).closest('form').attr('action', '{{route("activityReport.create")}}');
            $(this).closest('form').attr('method', 'GET');
            $(this).closest('form').submit();
        });
        
        $('#mining_activity_id').change(function(){
            var option = $('option:selected', this).attr('data-maximum');
            $('#quantity').attr('max', option).val('1');
            console.log('El máximo del campo cantidad es = ' + $('#quantity').attr('max'));
        });
        
        {{-- Initialize all tooltips --}}
        $('[data-toggle="tooltip"]').tooltip();
        
    
         $(".bootstrap_switch").bootstrapSwitch();
    
    {{-- *************************************** --}}
    
	
$(function() {
    
        $('#calendar-trigger').daterangepicker({
            "singleDatePicker": true,
            "timePickerIncrement": 1,
            "autoApply": true,


            separator: ' hasta ',
            locale: {
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
            },
            "startDate": "{{Carbon\Carbon::now()->format('m/d/Y')}}",
            "endDate": "{{Carbon\Carbon::now()->format('m/d/Y')}}",
            "minDate": "{{Carbon\Carbon::now()->subDays(1)->format('m/d/Y')}}",
            "maxDate": "{{Carbon\Carbon::now()->format('m/d/Y')}}",
            "opens": "left",
            "drops": "down",
            "buttonClasses": "btn btn-sm",
            "applyClass": "btn-success",
            "cancelClass": "btn-default"
        }, function(start, end, label) {
            $('#reported_at').attr('value', start.format('YYYY-MM-DD'));
        });
    
    });
    
    $('input[name="attended"]').on('switchChange.bootstrapSwitch', function(event, state) {
        
        var employee_id = $('#employee_id').val();
        var url = "{{route('noveltyReport.create')}}";
        
        url += employee_id ? '?employee_id='+employee_id : '' ;
        
        if(state === false)
            window.location.replace(url);
    });
    
</script>

@endsection

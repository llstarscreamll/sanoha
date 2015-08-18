@extends('app')

@section('title')
	Reportar Novedad
@stop

@section('content')

	<div class="">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Reportar Novedad <small>{{\Session::get('current_cost_center_name')}}</small>
				</h1>
			</div>

			<div class="panel-body">
			    
			    @include ('layout.notifications')

				{!! Form::open(['route' => 'noveltyReport.store', 'method' => 'POST']) !!}
				
				    @include('noveltyReports.partials.create-edit-form')
				
			        <div class="col-md-6 col-md-offset-3">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                            Reportar
                        </button>
                    </div>
                    
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
    
$(function() {
    
    $(".bootstrap_switch").bootstrapSwitch();
    
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
        var url = "{{route('activityReport.create')}}";
        
        url += employee_id ? '?employee_id='+employee_id : '' ;

        if(state === true)
            window.location.replace(url);
    });
    
</script>

@endsection
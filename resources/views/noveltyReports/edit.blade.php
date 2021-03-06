@extends('app')

@section('title')
	Editar Novedad
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					<a class="link-black" href="{{route('noveltyReport.index')}}">Reportes de Novedad</a> <small>{{\Session::get('current_cost_center_name')}}</small>
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{!! Form::model($novelty, ['route' => ['noveltyReport.update', $novelty->id], 'method' => 'PUT']) !!}
				
				    <fieldset>
                        <legend class="col-md-6 col-md-offset-3">Actualizar Detalles de Novedad</legend>
				        @include('noveltyReports.partials.create-edit-form')
				    </fieldset>
				
				<div class="col-md-6 col-md-offset-3">
    				<button class="btn btn-warning">
    			        <span class="glyphicon glyphicon-pencil"></span>
    			        Actualizar
    				</button>
				</div>
				
				{!!Form::close()!!}

			</div>
		</div>
	</div>
@endsection

@section('script')
    
    {{-- Include Date Range Picker --}}
	<script type="text/javascript" src="{{ asset('/resources/moment/min/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/resources/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('/resources/bootstrap-daterangepicker/daterangepicker.css') }}" />

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
        
        {{-- Si se va a actualizar la ruta, se puede poner la fecha que quiera, es decir se quitan los limites para reportar --}}
        @if(!\Route::currentRouteName() == 'noveltyReport.edit')
            "minDate": "{{Carbon\Carbon::now()->subDays(1)->format('m/d/Y')}}",
            "maxDate": "{{Carbon\Carbon::now()->format('m/d/Y')}}",
        @endif
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
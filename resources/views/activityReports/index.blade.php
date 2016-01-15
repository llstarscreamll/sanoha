@extends('app')

@section('title')
	Nómina de Labores Mineras
@stop

@section('content')
	
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					<a href="{{ url(route('activityReport.individual'))}}" class="link-black">
						Nómina de Labores Mineras
					</a>					
					@include ('activityReports.partials.action-buttons')
					
				</h1>
				
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				<div class="row hidden-print">
				    
				    @include ('activityReports.partials.views-links')
				    @include ('activityReports.partials.searchForm', ['search_target' => 'activityReport.index'])
				    
				</div>
				
				
				@if(count($orderedActivities) > 0)
				
					@include ('activityReports.partials.indexTable')
					
				@else
				
					<div class="alert alert-danger margin-top-10">
						No se encontraron registros...
					</div>
				
				@endif

			</div>
		</div>
	</div>
	
@endsection

@section('script')

	{{-- Include Date Range Picker --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/resources/bootstrap-daterangepicker/daterangepicker.css') }}" />
    <script type="text/javascript" src="{{ asset('/resources/moment/min/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/resources/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    
    <script type="text/javascript">
        $(function() {
        {{-- Configura daterangepicker --}}
        var fromField = $('#from').val();
        var toField = $('#to').val();
        $('#reportrange').daterangepicker({
            format: 'MM/DD/YYYY',
            startDate: moment(fromField != '' ? fromField : moment().format('YYYY-MM-DD'), 'YYYY-MM-DD'),
            endDate: moment(toField != '' ? toField : moment().format('YYYY-MM-DD'), 'YYYY-MM-DD'),
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            ranges: {
               'Hoy': [moment(), moment()],
               'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
               'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
               'Este Mes': [moment().startOf('month'), moment().endOf('month')],
               'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'left',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-danger',
            separator: ' hasta ',
            locale: {
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Personalizado',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
            }
        }, function(start, end, label) {
            $('#from').attr('value', start.format('YYYY-MM-DD'));
            $('#to').attr('value', end.format('YYYY-MM-DD'));
        });
        
        });
        
        {{-- Initialize all tooltips --}}
        $('[data-toggle="tooltip"]').tooltip();
            
    </script>

@stop()
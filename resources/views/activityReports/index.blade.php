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
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker-bs3.css" />
    
    <script type="text/javascript">
        $(function() {
        
        $('#reportrange').daterangepicker({
            format: 'MM/DD/YYYY',
            startDate: moment('{{$parameters["from"]}}', 'YYYY-MM-DD'),
            endDate: moment('{{ $parameters["to"] }}', 'YYYY-MM-DD'),
            //minDate: '01/01/2012',
            //maxDate: moment().format('L'),
            //dateLimit: { days: 60 },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
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
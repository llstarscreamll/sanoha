@extends('app')

@section('title')
	Reportes de Novedad
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
				    <a href="{{ url(route('noveltyReport.index'))}}" class="link-black">
					    Reportes de Novedad
					</a>
					@include ('noveltyReports.partials.action-buttons')
					
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				<div class="row hidden-print">
				    @include ('noveltyReports.partials.views-links')
				    
					@include ('noveltyReports.partials.search-form', ['search_target' => 'noveltyReport.index'])
				</div>
				
				@include ('noveltyReports.partials.index-table')

			</div>
		</div>
	</div>
@endsection

@section('script')

    <script type="text/javascript">
        
        $(document).ready(function(){
        	
        	{{-- trigger form submit when click on action buttons --}}
        	$('.action-buttons button[type=submit]').click(function(){
        		$('form[name=table-form]').submit();
        	});
            
            {{-- searching if there are checkboxes checked to toggle enable action buttons --}}
            scanCheckedCheckboxes('.checkbox-table-item');
            
            {{-- toggle the checkbox checked state from row click --}}
            toggleCheckboxFromRowClick();
            
            {{-- toggle select/unselect all checkboxes --}}
            toggleCheckboxes();
            
            {{-- listen click on checkboxes to change row class and count the ones checked --}}
            $('.checkbox-table-item').click(function(event) {
                scanCheckedCheckboxes('.'+$(this).attr('class'));
                event.stopPropagation();
            });
            
            {{-- Initialize all tooltips --}}
            $('[data-toggle="tooltip"]').tooltip();
            
        });
        
    </script>

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
            maxDate: moment().format('L'),
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
    </script>

@stop()

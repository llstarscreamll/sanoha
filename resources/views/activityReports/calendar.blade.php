@extends('app')

@section('title')
	Reporte de Labores Mineras
@stop

@section('style')
    {{-- Bootstrap Calendar --}}    
    <link href="{{ asset('/resources/bootstrap-calendar/css/calendar.css') }}" rel="stylesheet">
@stop

@section('content')

	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					<a href="{{ url(route('activityReport.individual'))}}" class="link-black">
						Reporte de Labores Mineras
					</a>
					
					@include ('activityReports.partials.action-buttons')
					
				</h1>
				
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
                <div class="row hidden-print">
				    @include ('activityReports.partials.views-links')
				    
				    @include ('activityReports.partials.searchForm', ['search_target' => 'activityReport.calendar'])
				</div>
                
				@if($activities != '[]')
    				
    				<div class="row">
        				<div class="col-xs-12">
                		    <h3 style="display:inline;">{{$parameters['cost_center_name']}}</h3>
  
                			<div class="btn-group pull-right">
                				<button class="btn btn-warning" data-calendar-view="year">Año</button>
                				<button class="btn btn-warning" data-calendar-view="month">Mes</button>
                				<button class="btn btn-warning" data-calendar-view="week">Semana</button>
                				<button class="btn btn-warning" data-calendar-view="day">Día</button>
                			</div>
                		</div>
            		</div>
            		
            		{{-- La capa del calendario --}}
    				<div class="col-md-10 col-md-offset-1">
    				    
    				    <div class="date-label text-center">
    				        <h3></h3>
    				    </div>
    				    
    				    <div id="calendar" class="margin-top-20"></div>
    				    
    				</div>
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
    
    {{-- Dependencia de los dos paquetes siguientes --}}
	<script src="{{ asset('/resources/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/resources/underscore/underscore-min.js') }}"></script>
    
    {{-- Bootstrap Calendar --}}
    <script src="{{ asset('/resources/bootstrap-calendar/js/language/es-ES.js') }}"></script>
    <script src="{{ asset('/resources/bootstrap-calendar/js/calendar.js') }}"></script>
    
	{{-- Include Date Range Picker --}}
	<script type="text/javascript" src="{{ asset('/resources/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('/resources/bootstrap-daterangepicker/daterangepicker.css') }}"/>

    <script type="text/javascript">
        
            {{-- ************************************
        	        Opciones de Bootstrap Calendar
        	     ************************************  --}}
        	(function($) {
            
            	"use strict";
            
            	var options = {
            		events_source: {!! $activities !!},
            		language: 'es-ES',
            		view: 'month',
            		tmpl_path: "{{url('resources/bootstrap-calendar/tmpls/').'/'}}",
            		tmpl_cache: true,
            		day: '{{$parameters["from"]->toDateString()}}',
            		onAfterEventsLoad: function(events) {
            			if(!events) {
            				return;
            			}
            			var list = $('#eventlist');
            			list.html('');
            
            			$.each(events, function(key, val) {
            				$(document.createElement('li'))
            					.html('<a href="' + val.url + '">' + val.title + '</a>')
            					.appendTo(list);
            			});
            		},
            		onAfterViewLoad: function(view) {
            			$('.date-label h3').text(this.getTitle());
            			$('.btn-group button').removeClass('active');
            			$('button[data-calendar-view="' + view + '"]').addClass('active');
            		},
            		classes: {
            			months: {
            				general: 'label'
            			}
            		}
            	};
            
            	var calendar = $('#calendar').calendar(options);
            
            	$('.btn-group button[data-calendar-nav]').each(function() {
            		var $this = $(this);
            		$this.click(function() {
            			calendar.navigate($this.data('calendar-nav'));
            		});
            	});
            
            	$('.btn-group button[data-calendar-view]').each(function() {
            		var $this = $(this);
            		$this.click(function() {
            			calendar.view($this.data('calendar-view'));
            		});
            	});
            
            	$('#show_wbn').change(function(){
            		var val = $(this).is(':checked') ? true : false;
            		calendar.setOptions({display_week_numbers: val});
            		calendar.view();
            	});
            	$('#show_wb').change(function(){
            		var val = $(this).is(':checked') ? true : false;
            		calendar.setOptions({weekbox: val});
            		calendar.view();
            	});
            }(jQuery));
        	
        	{{-- **********************************************
        	        Fin de opciones de Bootstrap Calendar
        	     ********************************************** --}}

        $(document).ready(function(){
            
        	{{-- trigger form submit when click on action buttons --}}
        	$('.action-buttons button[type=submit]').click(function(){
        		$('form[name=table-form]').submit();
        	});

            
            {{-- searching if there are checkboxes checked to toggle enable action buttons --}}
            scanCheckedCheckboxes('.checkbox-table-item');

            
            {{-- toggle the checkbox checked state from row click --}}
            toggleCheckboxFromRowClick();

            
            {{-- toggle select all checkboxes --}}
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
    
    
    <script type="text/javascript">
$(function() {

    $('#reportrange').daterangepicker({
        format: 'MM/DD/YYYY',
        linkedCalendars: false,
        {{--
            Aquí dejamos las opciones diferentes porque aunque no se den parametros de búsqueda, siempre
            se cargan unos parámetros de fecha por defecto, que son el inicio y fin de mes
        --}}
        startDate: moment('{{$parameters["from"]}}', 'YYYY-MM-DD'),
        endDate: moment('{{ $parameters["to"] }}', 'YYYY-MM-DD'),
        dateLimit: { months: 24 },
        showDropdowns: true,
        showWeekNumbers: true,
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
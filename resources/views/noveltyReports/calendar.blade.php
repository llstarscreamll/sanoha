@extends('app')

@section('title')
	Reportes de Novedad
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
				    
				   @include ('noveltyReports.partials.search-form', ['search_target' => 'noveltyReport.calendar'])
				</div>
                
				@if($json_novelties != '[]')
    				
    				<div class="row">
        				<div class="col-xs-12">
                		    <h3 style="display:inline;">{{\Session::get('current_cost_center_name')}}</h3>
  
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
    <!--
    <script src="{{ asset('/resources/moment/moment.js') }}"></script>
    -->
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
    <!--
    -->
    <script src="{{ asset('/resources/underscore/underscore-min.js') }}"></script>
    
    {{-- Bootstrap Calendar --}}
    <script src="{{ asset('/resources/bootstrap-calendar/js/language/es-ES.js') }}"></script>
    <script src="{{ asset('/resources/bootstrap-calendar/js/calendar.js') }}"></script>
    
	{{-- Include Date Range Picker --}}
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker-bs3.css" />


    <script type="text/javascript">
        
            {{-- ************************************
        	        Opciones de Bootstrap Calendar
        	     ************************************  --}}
        	(function($) {
            
            	"use strict";
            
            	var options = {
            		events_source: {!! $json_novelties !!},
            		language: 'es-ES',
            		view: 'month',
            		tmpl_path: "{{url('resources/bootstrap-calendar/tmpls/').'/'}}",
            		tmpl_cache: false,
            		day: '{{ date("Y-m-d") }}',
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
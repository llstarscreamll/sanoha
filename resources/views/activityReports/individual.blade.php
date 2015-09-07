@extends('app')

@section('title')
	Reporte de Labores Mineras
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
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
				    
				    @include ('activityReports.partials.searchForm', ['search_target' => 'activityReport.individual'])
				</div>
				
				<div class="table-responsive margin-top-10">
					{!! Form::model($activities, ['route' => ['activityReport.destroy'], 'method' => 'DELETE', 'name' => 'table-form']) !!}
					
				    <table class="table table-hover table-bordered table-vertical-align-middle">
				        
				        <thead>
				        	<tr>
				        		<th colspan=6>
				        			<h3>{{\Session::get('current_cost_center_name')}}</h3>
				        		</th>
				        		<th colspan=2>
				        			<h4 class="text-right">
										<small>Desde</small> {{ $parameters['from']->format('d-m-Y') }}
										<br>
										<small>Hasta</small> {{ $parameters['to']->format('d-m-Y') }}
									</h4>
				        		</th>
				        	</tr>
				            <tr>
				            	<th>{!! Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all']) !!}</th>
				                <th>#</th>
				                <th>Empleado</th>
				                <th>Actividad</th>
				                <th>Cantidad</th>
				                <th>Precio</th>
				                <th>Total</th>
				                <th>Fecha</th>
				            </tr>
				        </thead>
				        
				        <tbody>
				        	@if(count($activities) > 0)
					            @foreach($activities as $activity)
					            	
					            	<tr>
					            		<td>{!! Form::checkbox('id[]', $activity->id, false, ['class' => 'checkbox-table-item', 'id' => 'activity-'.$activity->id]) !!}</td>
					            		<td><a href="{{route('activityReport.show', $activity->id)}}">{{$activity->id}}</a></td>
					            		<td>{{ucwords(strtolower($activity->employee->fullname))}}</td>
					            		<td>{{$activity->miningActivity->name}}</td>
					            		<td>{{$activity->quantity}}</td>
					            		<td>{{number_format($activity->price, 0, ',', '.')}}</td>
					            		<td>{{number_format($activity->quantity * $activity->price, 0, ',', '.')}}</td>
					            		<td>{{$activity->reported_at->toDateString()}}</td>
					            	</tr>
					            	
					            @endforeach
							@else
							
								<tr>
									<td colspan=7>
										<div class="alert alert-danger">
											No se encontraron registros...
										</div>
									</td>
								</tr>
							
							@endif
				        </tbody>
				        
				    </table>
				    
				    {!! Form::close() !!}
				</div>
				{!!$activities->render()!!}

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
        
        {{-- Initialize all tooltips --}}
        $('[data-toggle="tooltip"]').tooltip();
</script>

@stop()
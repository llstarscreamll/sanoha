@extends('app')

@section('title', 'Ordenes de Trabajo')

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					<a class="link-black" href="{{route('workOrder.index')}}">Ordenes de Trabajo</a>
					
					<small class="action-buttons btn-group">
						
						{{-- Action Buttons --}}
						<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Mover a Papelera">
							<button type="submit" class="btn btn-default btn-sm" id="btn-trash">
								<span class="glyphicon glyphicon-trash"></span>
								<span class="sr-only">Mover Usuarios a Papelera</span>
							</button>
						</div>
						
						<div class="display-inline-block"  role="button"  data-toggle="tooltip" data-placement="top" title="Crear Orden de Trabajo">
							<a id="create-user-link" class="btn btn-default btn-sm" href="{!! route('workOrder.create') !!}">
								<span class="glyphicon glyphicon-plus"></span>
								<span class="sr-only">Crear Orden de Trabajo</span>
							</a>
						</div>
					</small>
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')

				<div class="row hidden-print">
					<div class="col-md-6"></div>

					@include ('workOrders.partials.search-form')
				</div>
				
				<div class="table-responsive">
					<table class="table table-hover">
						
						<thead>
							<tr>
								<th>#</th>
								<th>Destino</th>
								<th>Vehículo</th>
								<th>Responsable</th>
								<th>Autorizado Por</th>
								<th>Fecha</th>
								<th>Acciones</th>
							</tr>
						</thead>
						
						<tbody>
							@if(count($workOrders) > 0)
							
								@foreach($workOrders as $workOrder)
								<tr>
									<td><a href="{{route('workOrder.show', $workOrder->id)}}">{{$workOrder->id}}</a></td>
									<td>{{$workOrder->destination}}</td>
									<td>{{$workOrder->vehicle->plate}}</td>
									<td>{{$workOrder->employee->fullname}}</td>
									<td>{{$workOrder->user->fullname}}</td>
									<td>{{$workOrder->created_at->toDateString()}}</td>
									<td>
										<a
											href="{{$workOrder->hasVehicleMovement('exit') ? '#' : route('workOrder.vehicleMovementForm', ['work_order_id' => $workOrder->id, 'action' => 'exit'])}}"
											class="btn btn-xs btn-danger {{ $workOrder->hasVehicleMovement('exit') ? 'disabled' : null }}">
											<span class="glyphicon glyphicon-log-out"></span>
											<span class="sr-only">Registrar Salida</span>
										</a>

										{{-- Si se ha registrado la salida del vehículo de la orden, entonces muestro el botón de registar la entrada --}}

										@if($workOrder->hasVehicleMovement('exit'))
											<a
											href="{{$workOrder->hasVehicleMovement('entry') ? '#' : route('workOrder.vehicleMovementForm', ['work_order_id' => $workOrder->id, 'action' => 'entry'])}}"
											class="btn btn-xs btn-success {{ $workOrder->hasVehicleMovement('entry') ? 'disabled' : null }}">
												<span class="glyphicon glyphicon-log-in"></span>
												<span class="sr-only">Registrar Entrada</span>
											</a>
										@endif

									</td>
								</tr>
								@endforeach
								
							@else
							
								<tr>
									<td colspan=7>
										<div class="alert alert-warning">
											No se encontraron ordenes de trabajo....
										</div>
									</td>
								</tr>
							
							@endif
						</tbody>
						
					</table>
				</div>
				
				{!! $workOrders->render() !!}

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

            {{-- Configura daterangepicker --}}
            var fromField = $('#from').val();
            var toField = $('#to').val();
            $('#reportrange').daterangepicker({
            format: 'MM/DD/YYYY',
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            startDate: moment(fromField != '' ? fromField : moment().format('YYYY-MM-DD'), 'YYYY-MM-DD'),
            endDate: moment(toField != '' ? toField : moment().format('YYYY-MM-DD'), 'YYYY-MM-DD'),
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
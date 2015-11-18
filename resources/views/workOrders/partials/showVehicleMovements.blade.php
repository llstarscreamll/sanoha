<div class="col-md-10 col-md-offset-1">

	@if(count($workOrder->vehicleMovements) > 0)

		@foreach($workOrder->vehicleMovements as $movement)

			<div class="col-md-6 margin-top-10">
				<table id="{{$movement->action}}" class="table table-hover">
						<tr>
							<th class="{{$movement->action == 'exit' ? 'alert-danger' : 'alert-success'}} text-center" colspan=2>
								{{$movement->action == 'exit' ? 'Salida' : 'Entrada'}}
							</th>
						</tr>
						<tr>
							<th>Kilometraje</th>
							<td>{{$movement->mileage}}</td>
						</tr>
						<tr>
							<th>Combustible</th>
							<td>{{$movement->fuel_level}}</td>
						</tr>
						<tr>
							<th>Aseo Interno</th>
							<td>{{$movement->internal_cleanliness}}</td>
						</tr>
						<tr>
							<th>Aseo Externo</th>
							<td>{{$movement->external_cleanliness}}</td>
						</tr>
						<tr>
							<th>Pintura</th>
							<td>{{$movement->paint_condition}}</td>
						</tr>
						<tr>
							<th>Carrocería</th>
							<td>{{$movement->bodywork_condition}}</td>
						</tr>

						<tr>
							<th colspan=2 class="text-center">Llantas</th>
						</tr>

						<tr>
							<th>Delantera Rerecha</th>
							<td>{{$movement->right_front_wheel_condition}}</td>
						</tr>
						<tr>
							<th>Delantera Izquierda</th>
							<td>{{$movement->left_front_wheel_condition}}</td>
						</tr>
						<tr>
							<th>Trasera Rerecha</th>
							<td>{{$movement->rear_right_wheel_condition}}</td>
						</tr>
						<tr>
							<th>Trasera Izquierda</th>
							<td>{{$movement->rear_left_wheel_condition}}</td>
						</tr>
						<tr>
							<th>Comentario</th>
							<td>{{$movement->comment}}</td>
						</tr>

						<tr>
							<th colspan=2 class="text-center">Usuario</th>
						</tr>
						<tr>
							<th>Registado Por</th>
							<td>{{$movement->registeredBy->fullname}}</td>
						</tr>
						<tr>
							<th>Fecha y Hora</th>
							<td>{{$movement->created_at}}</td>
						</tr>
				</table>
			</div>

		@endforeach

		@if(count($workOrder->vehicleMovements) == 1)
			<div class="col-md-6 margin-top-10">
				<div class="alert alert-warning">
					No se ha registrado {{in_array('exit', $workOrder->vehicleMovements->lists('action', 'id')) ? 'entrada' : 'salida'}} del vehículo
				</div>
			</div>
		@endif
		
	@else

		<div class="col-md-10 col-md-offset-1 alert alert-danger margin-top-10">
			No se han registrado movimientos del vehículo...
		</div>

	@endif
	
</div>
<div class="table-responsive margin-top-10">
	<table class="table table-hover table-bordered table-condensed table-vertical-align-middle">
		<thead>
			<tr>
				<th class="vertical-align-middle" colspan=8>
					<h3 class="vertical-align-middle">{{ $parameters['cost_center_name'] }}</h3>
				</th>
				
				<th class="vertical-align-middle" colspan=6>
					{{-- Los usuario del sistema que reportaron las actividades --}}
					<h4 class="vertical-align-middle hidden-print">
						<small>Reportado por {{ count($orderedActivities['reported_by']) }} usuarios:</small>
						
						@foreach($orderedActivities['reported_by'] as $key => $value)
							<br>
							<a href="{{ route('users.show', $key) }}">
								{{$value}}
							</a>
						
						@endforeach
					</h4>
					
					{{-- El usuario que está imprimiendo el reporte --}}
					<h4 class="vertical-align-middle visible-print-block">
						<small>Impreso por:</small> {{\Auth::getUser()->fullname}}
					</h4>
				</th>
				
				<th class="vertical-align-middle" colspan={{ count($miningActivities)-12 }}>
					<h4 class="text-right">
						<small>Desde</small> {{ $parameters['from']->format('d-m-Y') }}
						<br>
						<small>Hasta</small> {{ $parameters['to']->format('d-m-Y') }}
					</h4>
				</th>
			</tr>
			
			<tr>
				<th>
					Nombres
				</th>
				
				@foreach($miningActivities as $activity)
					<th class="text-center">
						<span title="{{$activity['name']}}" data-toggle="tooltip">
							{{ $activity['short_name'] }}
						</span>
					</th>
				@endforeach
				<th class="text-center">
					<span title="Total Empleado" data-toggle="tooltip">
						TOTAL
					</span>
				</th>
			</tr>
			
		</thead>
		
		<tbody>
			
			{{-- No print the reported_by data --}}
			@foreach($orderedActivities as $key => $activity)

				@if($key !== 'reported_by')
				<tr class="{{ $key === 'totals' ? 'text-bold' : '' }}">
					
					@foreach($activity as $k => $v)

						@if(!is_array($v))
							<td class="{{ $k !== 'employee_fullname' ? 'text-center' : '' }}">
								<span>{{$v}}</span> {{-- El nombre del empelado --}}
							</td>
						@else
							<td class="{{ $k !== 'employee_fullname' ? 'text-center' : '' }}">
								<span data-toggle="tooltip" data-placement="top" title="{{$k == 'employee_total' ? 'Total Actividades '.$v['employee'] : 'Cantidad '.$k}}">
									{{ $v['quantity'] }}
								</span><br>
								<div>
									<span data-toggle="tooltip" data-placement="bottom" title="{{$k == 'employee_total' ? 'Precio Total '.$v['employee'] : 'Precio '.$k}}">
										{{number_format($v['price'], 0, ',', '.')}}
									</span>
								</div>
							</td>
						@endif
						
					@endforeach
					
				</tr>
				@endif

			@endforeach
			
		</tbody>
	</table>
</div>
	</table>
</div>	
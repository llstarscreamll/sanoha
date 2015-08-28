<div class="table-responsive margin-top-10">
	<table class="table table-hover table-bordered table-vertical-align-middle">
		<thead>
			<tr>
				<th class="vertical-align-middle" colspan=7>
					<h3 class="vertical-align-middle">{{ $parameters['costCenter_name'] }}</h3>
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
						<small>Impreso por:</small> {{\Auth::getUser()->getFullName()}}
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
				
			</tr>
		</thead>
		
		<tbody class="">
			
			{{-- No print the reported_by data --}}
			@foreach($orderedActivities as $key => $activity)

				@if($key !== 'reported_by')
				<tr class="{{ $key === 'totals' ? 'text-bold' : '' }}">
					
					@foreach($activity as $k => $v)

						@if(!is_array($v))
						<td class="{{ $k !== 'employee_fullname' ? 'text-center' : '' }}">
							<span>{{$v}}</span>
						</td>
						@else
						<td class="{{ $k !== 'employee_fullname' ? 'text-center' : '' }}">
							<span data-toggle="tooltip" data-placement="top" title="Cantidad {{$k}}">{{ $v['quantity'] }}</span><br>
						<div>
							<span data-toggle="tooltip" data-placement="bottom" title="Valor de los {{$k}}">{{number_format($v['price'], 0, ',', '.')}}</span>
						</div>
						</td>
						@endif
						
					@endforeach
					
				</tr>
				@endif

			@endforeach
			
			{{--
					@foreach($activity as $k => $v)
						@if($key !== $k.'_price')
						<td class="{{ $k !== 'employee_fullname' ? 'text-center' : '' }} ">
							{{ $v }}
						</td>
						@endif
					@endforeach
					--}}
			
		</tbody>
	</table>
</div>
	</table>
</div>	
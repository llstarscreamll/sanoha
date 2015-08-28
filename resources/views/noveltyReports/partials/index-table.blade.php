{!! Form::model($novelties, ['route' => ['noveltyReport.destroy'], 'method' => 'DELETE', 'name' => 'table-form']) !!}
				
				<div class="table-responsive">
				    
				    <table class="table table-hover">
				        <thead>
				        	<tr>
				        		<th colspan=5 class="text-center">
				        			{{\Session::get('current_cost_center_name', 'No se ha seleccionado centro de costo...')}}
				        		</th>
				        	</tr>
					        <tr>
					        	<th>{!! Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all']) !!}</th>
					        	<th>#</th>
					            <th>Nombres</th>
					            <th>Novedad</th>
					            <th>Fecha</th>
					        </tr>
				        </thead>
				        
				        <tbody>
				        	@if(count($novelties) > 0)
						        @foreach($novelties as $novelty)
						        	<tr>
						        		<td>{!! Form::checkbox('id[]', $novelty->id, false, ['class' => 'checkbox-table-item', 'id' => 'novelty-report-'.$novelty->id]) !!}</td>
						        		<td><a href="{{route('noveltyReport.show', [$novelty->id])}}">{{$novelty->id}}</a></td>
						        		<td>{{ucwords(strtolower($novelty->employee->fullname))}}</td>
						        		<td>{{$novelty->novelty->name}}</td>
						        		<td>{{$novelty->reported_at->toDateString()}}</td>
						        	</tr>
						        @endforeach
						    @else
						    	<tr>
						    		<td colspan=5>
						    			<div class="alert alert-warning">
						    				No se encontraron novedades...
						    			</div>
						    		</td>
						    	</tr>
						    @endif
				        </tbody>
				    </table>
				    	
				    	<div class="hidden-print">
					        {{-- Paginador --}}
							{!! $novelties->appends($search_input)->render() !!}
				    	</div>
				</div>
				
				{!! Form::close() !!}
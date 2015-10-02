{!! Form::model($users, ['route' => ['users.destroy'], 'method' => 'DELETE', 'name' => 'table-form']) !!}

	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>{!! Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all']) !!}</th>
					<th>Nombre</th>
					<th>Rol</th>
					<th>Email</th>
					<th>Estado</th>
				</tr>
			</thead>
	
			<tbody>
				
			@if(count($users) > 0)
			
				@foreach($users as $user)
				
					<tr>
						<td>{!! Form::checkbox('id[]', $user->id, false, ['class' => 'checkbox-table-item', 'id' => 'user-'.$user->id]) !!}</td>
						<td><a href="{{ route('users.show', $user->id) }}"> {{ $user->fullname }} </a></td>
						<td>
							{{ empty($user->getRoles()) ? 'Indefinido' : $user->getRoles()}}
						</td>
						<td>{{ $user->email }}</td>
						<td>{!! $user->getHtmlActivatedState() !!}</td>
					</tr>
					
				@endforeach
				
			@else
			
			<tr>
				<td colspan="4">
					<div class="alert alert-danger">
						No hay usuarios en la base de datos.
					</div>
				</td>
			</tr>
			
			@endif
			</tbody>
		</table>
	</div>
	
	{{-- Paginador --}}
	{!! $users->appends($input)->render() !!}
	
{!! Form::close() !!}
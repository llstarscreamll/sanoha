<div class="table-responsive">
	{!! Form::model($employees, ['route' => ['employee.destroy'], 'method' => 'DELETE', 'name' => 'table-form']) !!}
    <table class="table table-hover">
        
        <thead>
            <tr>
                <th>{!! Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all']) !!}</th>
                <th>Nombres</th>
                <th>Cargo</th>
                <th>Centro Costo</th>
                <th>Email</th>
                <th>Estado</th>
            </tr>
        </thead>
        
        <tbody>
            @if(count($employees) > 0)
            @foreach($employees as $employee)
		        <tr>
		            <td>{!! Form::checkbox('id[]', $employee->id, false, ['class' => 'checkbox-table-item', 'id' => 'employee-'.$employee->id]) !!}</td>
		            <td><a href="{{route('employee.show', $employee->id)}}">{{$employee->fullname}}</a></td>
		            <td>{{$employee->position->name}}</td>
		            <td>{{$employee->subCostCenter->nameWithCostCenterName}}</td>
		            <td>{{$employee->email}}</td>
		            <td>{!!$employee->getStatusHtml()!!}</td>
		        </tr>
	        @endforeach
	        @else
	            <tr>
	                <td colspan=6>
	                    <div class="alert alert-warning">
	                        No se encontraron empleados...
	                    </div>
	                </td>
	            </tr>
	        @endif
        </tbody>
    </table>
    {!! Form::close() !!}
</div>

{{-- Paginador --}}
{!! $employees->render() !!}
{!! Form::model($roles, ['route' => ['roles.destroy'], 'method' => 'DELETE', 'name' => 'table-form']) !!}

    <div class="table-responsive">
        
        <table class="table">
            
            <thead>
                <tr>
                    <th>{!! Form::checkbox('check_all', 'check_all', null, ['id' => 'check_all']) !!}</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{!! Form::checkbox('id[]', $role->id, null, ['class' => 'checkbox-table-item', 'id' => 'role-'.$role->id]) !!}</td>
                        <td>{!! Html::link(route('roles.show', $role->id), $role->display_name) !!}</td>
                        <td>{{ $role->description }}</td>
                    </tr>
                @endforeach
            </tbody>
            
        </table>
        
    </div>
    
{!! Form::close() !!}
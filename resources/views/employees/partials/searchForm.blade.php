<div class="col-md-6 col-md-offset-6">
{!! Form::model($input, ['route' => 'employee.index', 'method' => 'GET', 'name' => 'search-form']) !!}

    <div class="input-group">
                
        {!! Form::text('find', null, ['placeholder' => 'Buscar por nombre, apellidos o cédula...', 'class' => 'form-control']) !!}
        
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">
                <span class="glyphicon glyphicon-search"></span>
                <span class="sr-only">Buscar</span>
            </button>
            <a href="{{route('employee.index')}}" class="btn btn-default">
                <span class="glyphicon glyphicon-remove"></span>
                <span class="sr-only">Quitar Filtros</span>
            </a>
        </span>
        
    </div>

{!! Form::close() !!}
</div>
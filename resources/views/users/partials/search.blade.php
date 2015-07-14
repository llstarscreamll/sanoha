{!! Form::model($input, ['route' => 'users.index', 'method' => 'GET']) !!}

    <div class="row">
        
        <div class="col-md-6">
            
            <div class="input-group">
                
                {!! Form::text('find', null, ['placeholder' => 'Buscar...', 'class' => 'form-control']) !!}
                
                
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                        <span class="sr-only">Buscar</span>
                    </button>
                    <a href="{{ url(route('users.index')) }}" class="btn btn-default">
                        <span class="glyphicon glyphicon-remove"></span>
                        <span class="sr-only">Quitar Filtros</span>
                    </a>
                </span>
                
            </div>
            
            @if ($errors->has('find'))
                <div class="text-danger">
                    {!! $errors->first('find') !!}
                </div>
            @endif
            
        </div>
        
    </div>

{!! Form::close() !!}
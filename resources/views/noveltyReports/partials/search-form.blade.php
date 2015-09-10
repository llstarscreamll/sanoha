<div class="col-md-6">

    {!! Form::model($search_input, ['route' => $search_target, 'method' => 'GET', 'class' => 'hidden-print' ,'name' => 'search']) !!}

            <div class="input-group">
                
                {!! Form::text('find', null, ['placeholder' => 'Buscar por nombre, apellido o cédula...', 'class' => 'form-control']) !!}
                
                
                <span class="input-group-btn">
                    <button id="reportrange" class="btn btn-default" type="button" data-toggle="tooltip" data-placement="top" title="Buscar por Fechas">
                        <span class="glyphicon glyphicon-calendar"></span>
                        <span class="sr-only">Rango de Fechas</span>
                    </button>
                    
                    <button class="btn btn-default" type="submit" data-toggle="tooltip" data-placement="top" title="Buscar">
                        <span class="glyphicon glyphicon-search"></span>
                        <span class="sr-only">Buscar</span>
                    </button>
                    
                    <a href="{{ route(Route::currentRouteName()) }}" class="btn btn-default" role="button"  data-toggle="tooltip" data-placement="top" title="Quitar Filtros">
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
            
            @if ($errors->has('from'))
                <div class="text-danger">
                    {!! $errors->first('from') !!}
                </div>
            @endif
            
            @if ($errors->has('to'))
                <div class="text-danger">
                    {!! $errors->first('to') !!}
                </div>
            @endif
            

        {!! Form::hidden(
            'from',
            is_null($parameters['from']) ? '' : $parameters['from']->toDateString(),
            ['id' => 'from']
        ) !!}
        
        {!! Form::hidden(
            'to',
           is_null($parameters['to']) ? '' : $parameters['to']->toDateString(),
            ['id' => 'to']
        ) !!}
        
    {!! Form::close() !!}
    
</div>
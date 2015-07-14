{!! Form::model($search_input, ['route' => 'activityReport.index', 'method' => 'GET', 'class' => 'hidden-print']) !!}

    <div class="row">
        
        <div class="col-md-4 col-md-offset-8">
            
            <div class="input-group">
                
                {!! Form::text('find', null, ['placeholder' => 'Buscar por nombres o cédula...', 'class' => 'form-control']) !!}
                
                
                <span class="input-group-btn">
                    <button id="reportrange" class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-calendar"></span>
                        <span class="sr-only">Rango de Fechas</span>
                    </button>
                    
                    <button class="btn btn-default" type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                        <span class="sr-only">Buscar</span>
                    </button>
                    
                    <a href="{{ url(route('activityReport.index')) }}" class="btn btn-default">
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

        {!! Form::hidden(
            'from',
            isset($search_input['from']) ? $search_input['from'] : \Carbon\Carbon::yesterday(),
            ['id' => 'from']
        ) !!}
        
        {!! Form::hidden(
            'to',
            isset($search_input['to']) ? $search_input['to'] : \Carbon\Carbon::yesterday(),
            ['id' => 'to']
        ) !!}
        
    </div>

{!! Form::close() !!}
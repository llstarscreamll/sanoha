@extends('app')

@section('title', 'Reporte de Labores Mineras')

@section('content')

	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					{{-- Section title --}}
					<a class="link-black" href="{{route('activityReport.individual')}}">Reporte de Labores Mineras</a> <small>{{\Session::get('current_cost_center_name')}}</small>
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{{-- Formulario --}}
				{!! Form::open([
					'route' => !$request->has('employee_id')
						? 'activityReport.newCreateForm'
						: 'activityReport.newStore',
					'method' => !$request->has('employee_id')
						? 'GET'
						: 'POST'
				]) !!}

					<div class="row">
						<div class="form-group col-md-6">
	                        {!! Form::label('employee_id', 'Trabajador') !!}
	                        {!! Form::select('employee_id',
	                        	['' => 'Selecciona al trabajador']+$employees,
	                        	$request->has('employee_id') ? $request->get('employee_id') : null,
	                        	[
	                        		'class' => 'form-control selectpicker show-tick',
                                    'data-live-search' => 'true'
	                        	]
	                        ) !!}
	                        
	                        @if ($errors->has('employee_id'))
	                        <div class="text-danger">
	                            {!! $errors->first('employee_id') !!}
	                        </div>
	                        @endif
	                    </div>

	                    @if($request->has('employee_id'))
	                    {{-- Campo informativo, redirege al reporte de novedad en caso de que se marque como No, o no se marque --}}
                        <div class="form-group col-sm-4 col-md-2">
                            {!! Form::label('attended', 'Asistió?') !!}
                            <div>
                                {!! Form::checkbox(
                                    'attended',
                                    '1',
                                    null,
                                    [
                                        'class'         => 'bootstrap_switch',
                                        'data-on-text'  => 'Si',
                                        'data-off-text' => 'No',
                                        'data-off-color'=> 'danger',
                                        'data-on-color' => 'success',
                                        'checked'
                                    ])
                                !!}
                            </div>
                            @if ($errors->has('attended'))
                                <div class="text-danger">
                                    {!! $errors->first('attended') !!}
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-sm-8 col-md-4">
                            <div class="form-group">
                            <label for="reported_at">Fecha de Actividades</label>
                            <div class="input-group">
                                {!! Form::text(
                                    'reported_at',
                                    null,
                                        [
                                            'class' => 'form-control',
                                            'id' => 'reported_at',
                                            'placeholder' => 'Elegir Fecha',
                                            'readonly'
                                        ]
                                ) !!}
                                <span id="calendar-trigger" class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </button>
                                </span>
                            </div>
                            @if ($errors->has('reported_at'))
                                <div class="text-danger">
                                    {!! $errors->first('reported_at') !!}
                                </div>
                            @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="clearfix"></div>
                    
                    @if($request->has('employee_id'))
                    <div class="row">

                    {{-- Recorro todas las actividades mineras y creo inputs de cantidad y precio por cada una que haya --}}
                    @foreach($miningActivities as $activity)
                    	<div class="col-xs-6 col-sm-2 margin-top-15">
                    		{{-- El label de la actividad --}}
                    		<div><strong>{{$activity['short_name']}}</strong></div>
                    		
                    		{{-- La cantidad --}}
                    		<div>
                    			{!! Form::number(
                    				'mining_activity['.$activity['id'].']',
                    				null,
                    				[
                    					'class' => 'form-control',
                    					'max' => $activity['maximum'],
                    					'min' => '0',
                    					'step' => '0.1',
                    					'placeholder' => 'Cantidad'
                    				]
                    			) !!}
                    		</div>

                    		{{-- El precio --}}
                    		<div>
                    			{!! Form::number(
                    				'mining_activity_price['.$activity['id'].']',
                    				($price = $miningActivityModel->getHistoricalActivityPrice(
                    					$activity['id'],
                    					\Session::get('current_cost_center_id'),
                    					$request->get('employee_id'))) != 0 ? $price : null,
                    				[
                    					'class' => 'form-control',
                    					'step' => '50',
                    					'placeholder' => 'Precio',
                                        Auth::user()->can('activityReport.assignCosts') ? '' : 'readonly' => 'readonly'
                    				]
                    			) !!}
                    		</div>
                    	</div>
                    @endforeach
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('comment', 'Comentario') !!}
                            {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => '3']) !!}
                            
                            @if ($errors->has('comment'))
                            <div class="text-danger">
                                {!! $errors->first('comment') !!}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                            Guardar
                        </button>
                    </div>

                    </div>
                    @else

                    <div class="col-sm-10 col-md-offset-1 alert alert-warning">Selecciona un trabajador...</div>
                    
                    @endif

				{!! Form::close() !!}

			</div>
		</div>
	</div>
@endsection

@section('script')
    {{-- Include Date Range Picker --}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker-bs3.css" />
	
    <script type="text/javascript">
        
        {{-- Initialize all tooltips --}}
        $('[data-toggle="tooltip"]').tooltip();

        {{-- inicializa el switch para los checkbox o radiobuttons --}}
        $(".bootstrap_switch").bootstrapSwitch()
        
        {{-- envía el formulario cuando el campo select del trabajador cambia --}}
        $("#employee_id").change(function(){
            $(this).closest('form').attr('action', '{{route("activityReport.newCreateForm")}}');
            $(this).closest('form').attr('method', 'GET');
            $(this).closest('form').submit();
        });

        {{-- inicializa el daterangerpicker con sus respectivas opciones --}}
        $('#calendar-trigger').daterangepicker({
            "singleDatePicker": true,
            "timePickerIncrement": 1,
            "autoApply": true,
            separator: ' hasta ',
            locale: {
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
            },
            "startDate": "{{Carbon\Carbon::now()->format('m/d/Y')}}",
            "endDate": "{{Carbon\Carbon::now()->format('m/d/Y')}}",
            /* Se quita la restricción por unos días mientras mientras se ponen al tanto con los reportes */
            //"minDate": "{{Carbon\Carbon::now()->subDays(1)->format('m/d/Y')}}",
            "maxDate": "{{Carbon\Carbon::now()->addDays(2)->format('m/d/Y')}}",
            "opens": "left",
            "drops": "down",
            "buttonClasses": "btn btn-sm",
            "applyClass": "btn-success",
            "cancelClass": "btn-default"
        }, function(start, end, label) {
            $('#reported_at').attr('value', start.format('YYYY-MM-DD'));
        });
    
        {{-- el campo informactivo attended (asistio?) redirecciona a la página de reporte de novedades si se deja desmarcado (unchecked) --}}
        $('input[name="attended"]').on('switchChange.bootstrapSwitch', function(event, state) {
            
            var employee_id = $('#employee_id').val();
            var url = "{{route('noveltyReport.create')}}";
            
            url += employee_id ? '?employee_id='+employee_id : '' ;
            
            if(state === false)
                window.location.replace(url);
        });
	</script>
@stop()

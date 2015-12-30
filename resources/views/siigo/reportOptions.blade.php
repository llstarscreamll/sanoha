@extends('app')

@section('title') Opciones del Reporte @stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>Siigo</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{!! Form::open(['method' => 'GET', 'route' => 'siigo.show']) !!}

					<div class="form-group col-sm-8 col-sm-offset-2">
						{!! Form::label('from', 'Rango de Fecha') !!}
						<div class="input-group">
							{!! Form::hidden('from', null, ['class' => 'form-control', 'id' => 'from']) !!}
							{!! Form::hidden('to', null, ['class' => 'form-control', 'id' => 'to']) !!}
							{!! Form::text('informative-date', null, ['class' => 'form-control', 'id' => 'informative-date']) !!}
							<span class="input-group-btn calendar-trigger">
		                        <button class="btn btn-default" type="button">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                        </button>
		                    </span>
	                    </div>
					</div>

					<div class="clearfix"></div>

					<div class="form-group col-sm-8 col-sm-offset-2">
		                {!! Form::label('nit', 'Nit') !!}
		                {!! Form::select('nit[]', $nits, null, ['id' => 'nit', 'multiple' => true, 'class' => 'form-control selectpicker', 'title' => 'Elige uno a varios nits', 'data-live-search' => 'true']) !!}
		                @if ($errors->has('nit'))
		                    <div class="text-danger">
		                        {!! $errors->first('nit') !!}
		                    </div>
		                @endif
		            </div>

		            <div class="clearfix"></div>
					
					<div class="form-group col-md-8 col-sm-offset-2">
					<button class="btn btn-primary">
						Solicitar Reporte
					</button>
					</div>

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
	$(function() {
    
        {{-- Configura daterangepicker --}}
        var fromField = $('#from').val();
        var toField = $('#to').val();
        $('.calendar-trigger').daterangepicker({
            format: 'MM/DD/YYYY',
            startDate: moment(fromField != '' ? fromField : moment().format('YYYY-MM-DD'), 'YYYY-MM-DD'),
            endDate: moment(toField != '' ? toField : moment().format('YYYY-MM-DD'), 'YYYY-MM-DD'),
            //minDate: '01/01/2012',
            //maxDate: moment().format('L'),
            //dateLimit: { days: 60 },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            ranges: {
               'Hoy': [moment(), moment()],
               'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
               'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
               'Este Mes': [moment().startOf('month'), moment().endOf('month')],
               'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'left',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-danger',
            separator: ' hasta ',
            locale: {
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Personalizado',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
            }
        }, function(start, end, label) {
            $('input[name=from]').attr('value', start.format('YYYY-MM-DD'));
            $('input[name=to]').attr('value', end.format('YYYY-MM-DD'));
            $('#informative-date').attr('value', 'Desde '+start.format('YYYY-MM-DD')+' Hasta '+ end.format('YYYY-MM-DD'));
        });
    
    });
    
</script>

@endsection

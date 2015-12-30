@extends('app')

@section('title')
	{{-- Here page title!! --}}
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Importar Datos de Siigo
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{!! Form::open(['route' => 'siigo.store', 'method' => 'POST', 'files' => true]) !!}

					<div class="form-group col-md-8 col-md-offset-2">
						@if(\Session::has('info'))
							{!! var_dump(\Session::get('info')) !!}
						@endif
						@if(\Session::has('error'))
							{!!\Session::get('error')!!}
						@endif
					</div>

                    <div class="form-group col-md-8 col-md-offset-2">
                        <label for="file">Selecciona archivo .xlsx</label>
                        <input type="file" name="file" id="file">
                        <p class="help-block">Selecciona el archivo que contiene los datos a importar.</p>
                        @if ($errors->has('file'))
                            <div class="text-danger">
                                {!! $errors->first('file') !!}
                            </div>
                        @endif
                    </div>

                    <div class="form-group col-md-8 col-md-offset-2">
    				    <button type="submit" class="btn btn-primary">
    						<span class="glyphicon glyphicon-import"></span>
    						Importar
    					</button>
    					<br>
    					<br>
    					@if($siigoRows > 0)
    					<a class="text-primary" href="{{route('siigo.show')}}">Generar Reporte de Estados de Cuentas, ({{$siigoRows}})</a>
    					@else
    					<a class="text-danger" href="#">No ha registros para generar Reporte de Estados de Cuentas</a>
    					@endif
    			    </div>
				
				{!! Form::close() !!}

			</div>
		</div>
	</div>
@endsection

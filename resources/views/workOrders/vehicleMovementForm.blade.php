@extends('app')

@section('title') Registrar {{ $action == 'exit' ? 'Salida' : 'Entrada' }} de Vehículo @stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading {{ $action == 'exit' ? 'bg-danger' : 'bg-success' }}">
				<h1>
					Registrar {{ $action == 'exit' ? 'Salida' : 'Entrada' }} de Vehículo
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{!! Form::open([
					'method'		=>	'POST',
					'route'			=>	[
						'workOrder.vehicleMovementStore',
						'work_order_id' =>	$workOrder->id,
						'action' 		=>	$action
					]
				]) !!}

					{{-- Área de información de la orden de trabajo --}}
					<div class="form-group col-md-6">
						{!! Form::label('authorized_by', 'Autorizado por') !!}
						{!! Form::text('authorized_by', $workOrder->user->fullname, ['disabled' => 'disabled', 'class' => 'form-control']) !!}
					</div>

					<div class="form-group col-md-6">
						{!! Form::label('vehicle_responsable', 'Resonsable') !!}
						{!! Form::text('vehicle_responsable', $workOrder->employee->fullname, ['disabled' => 'disabled', 'class' => 'form-control']) !!}
					</div>

					<div class="clearfix"></div>

					<div class="form-group col-md-6">
						{!! Form::label('vehicle', 'Vehículo') !!}
						{!! Form::text('vehicle', $workOrder->vehicle->plate, ['disabled' => 'disabled', 'class' => 'form-control']) !!}
					</div>

					<div class="form-group col-md-6">
						{!! Form::label('vehicle', 'Destino') !!}
						{!! Form::text('vehicle', $workOrder->destination, ['disabled' => 'disabled', 'class' => 'form-control']) !!}
					</div>
					
					<div class="clearfix"></div>

					{{-- Los elementos para registrar el estado en que sale o entra el vehóculo --}}
					<div  class="col-xs-12">
					<fieldset>
						<legend>Estado del Vehículo</legend>

						<div class="form-group col-md-6">
							{!! Form::label('mileage', 'Kilometraje') !!}
							{!! Form::number('mileage', null, ['class' => 'form-control', 'id' => 'mileage', 'step' => '1', 'min' => '1']) !!}
							@if ($errors->has('mileage'))
						        <div class="text-danger">
						            {!! $errors->first('mileage') !!}
						        </div>
						    @endif
						</div>

						<div class="form-group col-md-6">
							{!! Form::label('', 'Nivel de Gasolina') !!}
							<div class="clearfix"></div>
							<label class="radio-inline">
							    <input type="radio" name="fuel_level" value="1/4"> 1/4
							</label>
							<label class="radio-inline">
							    <input type="radio" name="fuel_level" value="1/2"> 1/2
							</label>
							<label class="radio-inline">
							    <input type="radio" name="fuel_level" value="3/4"> 3/4
							</label>
							<label class="radio-inline">
							    <input type="radio" name="fuel_level" value="1"> 1
							</label>
							@if ($errors->has('fuel_level'))
						        <div class="text-danger">
						            {!! $errors->first('fuel_level') !!}
						        </div>
						    @endif
						</div>

						<div class="clearfix"></div>

						<div class="form-group col-md-6">
							{!! Form::label('', 'Aseo Interior') !!}
							<div class="clearfix"></div>
							<label class="radio-inline">
							    <input type="radio" name="internal_cleanliness" value="Malo"> Malo
							</label>
							<label class="radio-inline">
							    <input type="radio" name="internal_cleanliness" value="Regular"> Regular
							</label>
							<label class="radio-inline">
							    <input type="radio" name="internal_cleanliness" value="Bueno"> Bueno
							</label>
							@if ($errors->has('internal_cleanliness'))
						        <div class="text-danger">
						            {!! $errors->first('internal_cleanliness') !!}
						        </div>
						    @endif
						</div>

						<div class="form-group col-md-6">
							{!! Form::label('', 'Aseo Exterior') !!}
							<div class="clearfix"></div>
							<label class="radio-inline">
							    <input type="radio" name="external_cleanliness" value="Malo"> Malo
							</label>
							<label class="radio-inline">
							    <input type="radio" name="external_cleanliness" value="Regular"> Regular
							</label>
							<label class="radio-inline">
							    <input type="radio" name="external_cleanliness" value="Bueno"> Bueno
							</label>
							@if ($errors->has('external_cleanliness'))
						        <div class="text-danger">
						            {!! $errors->first('external_cleanliness') !!}
						        </div>
						    @endif
						</div>

						<div class="clearfix"></div>

						<div class="form-group col-md-6">
							{!! Form::label('', 'Estado Pintura') !!}
							<div class="clearfix"></div>
							<label class="radio-inline">
							    <input type="radio" name="paint_condition" value="Malo"> Mala
							</label>
							<label class="radio-inline">
							    <input type="radio" name="paint_condition" value="Regular"> Regular
							</label>
							<label class="radio-inline">
							    <input type="radio" name="paint_condition" value="Bueno"> Buena
							</label>
							@if ($errors->has('paint_condition'))
						        <div class="text-danger">
						            {!! $errors->first('paint_condition') !!}
						        </div>
						    @endif
						</div>

						<div class="form-group col-md-6">
							{!! Form::label('', 'Estado Carrocería') !!}
							<div class="clearfix"></div>
							<label class="radio-inline">
							    <input type="radio" name="bodywork_condition" value="Malo"> Malo
							</label>
							<label class="radio-inline">
							    <input type="radio" name="bodywork_condition" value="Regular"> Regular
							</label>
							<label class="radio-inline">
							    <input type="radio" name="bodywork_condition" value="Bueno"> Bueno
							</label>
							@if ($errors->has('bodywork_condition'))
						        <div class="text-danger">
						            {!! $errors->first('bodywork_condition') !!}
						        </div>
						    @endif
						</div>

						<div class="form-group col-md-6">
							{!! Form::label('', 'Estado Llanta Delantera Derecha') !!}
							<div class="clearfix"></div>
							<label class="radio-inline">
							    <input type="radio" name="right_front_wheel_condition" value="Malo"> Malo
							</label>
							<label class="radio-inline">
							    <input type="radio" name="right_front_wheel_condition" value="Regular"> Regular
							</label>
							<label class="radio-inline">
							    <input type="radio" name="right_front_wheel_condition" value="Bueno"> Bueno
							</label>
							@if ($errors->has('right_front_wheel_condition'))
						        <div class="text-danger">
						            {!! $errors->first('right_front_wheel_condition') !!}
						        </div>
						    @endif
						</div>

						<div class="form-group col-md-6">
							{!! Form::label('', 'Estado Llanta Delantera Izquierda') !!}
							<div class="clearfix"></div>
							<label class="radio-inline">
							    <input type="radio" name="left_front_wheel_condition" value="Malo"> Malo
							</label>
							<label class="radio-inline">
							    <input type="radio" name="left_front_wheel_condition" value="Regular"> Regular
							</label>
							<label class="radio-inline">
							    <input type="radio" name="left_front_wheel_condition" value="Bueno"> Bueno
							</label>
							@if ($errors->has('left_front_wheel_condition'))
						        <div class="text-danger">
						            {!! $errors->first('left_front_wheel_condition') !!}
						        </div>
						    @endif
						</div>

						<div class="form-group col-md-6">
							{!! Form::label('', 'Estado Llanta Trasera Derecha') !!}
							<div class="clearfix"></div>
							<label class="radio-inline">
							    <input type="radio" name="rear_right_wheel_condition" value="Malo"> Malo
							</label>
							<label class="radio-inline">
							    <input type="radio" name="rear_right_wheel_condition" value="Regular"> Regular
							</label>
							<label class="radio-inline">
							    <input type="radio" name="rear_right_wheel_condition" value="Bueno"> Bueno
							</label>
							@if ($errors->has('rear_right_wheel_condition'))
						        <div class="text-danger">
						            {!! $errors->first('rear_right_wheel_condition') !!}
						        </div>
						    @endif
						</div>

						<div class="form-group col-md-6">
							{!! Form::label('', 'Estado Llanta Trasera Izquierda') !!}
							<div class="clearfix"></div>
							<label class="radio-inline">
							    <input type="radio" name="rear_left_wheel_condition" value="Malo"> Malo
							</label>
							<label class="radio-inline">
							    <input type="radio" name="rear_left_wheel_condition" value="Regular"> Regular
							</label>
							<label class="radio-inline">
							    <input type="radio" name="rear_left_wheel_condition" value="Bueno"> Bueno
							</label>
							@if ($errors->has('rear_left_wheel_condition'))
						        <div class="text-danger">
						            {!! $errors->first('rear_left_wheel_condition') !!}
						        </div>
						    @endif
						</div>


					</fieldset>
					</div>
					
					<div class="clearfix"></div>
					<div class="form-group col-md-6">
						{!! Form::label('comment', 'Comentario') !!}
						{!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => 3]) !!}
						@if ($errors->has('comment'))
						        <div class="text-danger">
						            {!! $errors->first('comment') !!}
						        </div>
						    @endif
					</div>

					<div class="col-md-12">
						<button class="btn {{$action == 'exit' ? 'btn-danger' : 'btn-success'}}">
							<span class="glyphicon glyphicon-floppy-disk"></span>
							Registrar {{$action == 'exit' ? 'Salida' : 'Entrada'}}
						</button>
					</div>

				{!! Form::close() !!}

			</div>
		</div>
	</div>
@endsection

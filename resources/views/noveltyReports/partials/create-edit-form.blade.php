<fieldset>
				        <legend>Detalles de Novedad</legend>
				        
				        {{-- Employee --}}
				        <div class="col-md-6 col-md-offset-3 form-group">
				            {!! Form::label('employee_id', 'Trabajador') !!}
                            {!! Form::select('employee_id', ['' => 'Selecciona al trabajador']+$employees, isset($novelty) ? $novelty->employee_id : $employee_id, ['class' => 'form-control selectpicker show-tick', 'id' => 'employee_id']) !!}
                            
                            
                            @if ($errors->has('employee_id'))
                            <div class="text-danger">
                                {!! $errors->first('employee_id') !!}
                            </div>
                            @endif
				        </div>
				        
				        {{-- Campo informativo, sirve de vinculo para pasar de registro de novedad a registro de labor minera --}}
				        <div class="col-md-6 col-md-offset-3">
                            <div class="form-group">
                                {!! Form::label('attended', 'Asistió?') !!}
                                <div>
                                    {!! Form::checkbox(
                                        'attended',
                                        '1',
                                        false,
                                        [
                                            'class'         => 'bootstrap_switch',
                                            'data-on-text'  => 'Si',
                                            'data-off-text' => 'No',
                                            'data-off-color'=> 'danger',
                                            'data-on-color' => 'success'
                                        ])
                                    !!}
                                </div>
                                @if ($errors->has('attended'))
                                    <div class="text-danger">
                                        {!! $errors->first('attended') !!}
                                    </div>
                                @endif
                            </div>
                        </div>
				        
				        {{-- Novelty Kind --}}
				        <div class="col-md-6 col-md-offset-3 form-group">
				            {!! Form::label('novelty_id', 'Tipo de Novedad') !!}
				            {!! Form::select('novelty_id', ['' => 'Seleccione el tipo de novedad']+$novelties, null, ['class' => 'form-control selectpicker', 'required']) !!}
				            
				            @if ($errors->has('novelty_id'))
                                <div class="text-danger">
                                    {!! $errors->first('novelty_id') !!}
                                </div>
                            @endif
				        </div>
				        
				        {{-- Reported at --}}
				        <div class="col-md-6 col-md-offset-3 form-group">
				            {!! Form::label('reported_at', 'Fecha de Reporte') !!}
				            <div class="input-group">
				                {!! Form::text('reported_at', isset($novelty) ? $novelty->reported_at->toDateString() : null, ['class' => 'form-control', 'readonly']) !!}
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
				        
				        {{-- Comment --}}
				        <div class="col-md-6 col-md-offset-3 form-group">
				            {!! Form::label('comment', 'Comentario') !!}
    				        {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => '4']) !!}
    				        
    				        @if ($errors->has('comment'))
                                <div class="text-danger">
                                    {!! $errors->first('comment') !!}
                                </div>
                            @endif
				        </div>
				        
				        
				    </fieldset>
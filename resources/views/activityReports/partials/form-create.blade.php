                    <div class="form-group col-md-6 col-md-offset-3">
                        {!! Form::label('employee_id', 'Trabajador') !!}
                        {!! Form::select('employee_id', ['' => 'Selecciona al trabajador']+$employees, $parameters['employee_id'], ['class' => 'form-control selectpicker show-tick', 'data-live-search' => 'true']) !!}
                        
                        @if ($errors->has('employee_id'))
                        <div class="text-danger">
                            {!! $errors->first('employee_id') !!}
                        </div>
                        @endif
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    @if(!is_null($parameters['employee_id']))
                    
                        @if(\Route::currentRouteName() == 'activityReport.create')
                        
                            {{-- Campo informativo, redirege al reporte de novedad en caso de que se marque como No, o no se marque --}}
                            <div class="form-group col-md-6 col-md-offset-3">
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
                        @endif
                        
                            <div class="clearfix"></div>
                            
                            <div class="form-group col-md-6 col-md-offset-3">
                                {!! Form::label('mining_activity_id', 'Labor Minera') !!}
                                <select name="mining_activity_id" id="mining_activity_id" class="form-control selectpicker show-tick">
                                    
                                    <option value="" data-maximum="" {{ empty(old('mining_activity_id')) ? '' : 'selected' }}>Selecciona una actividad</option>
                                    @foreach($miningActivities as $mActivity)
                                        <option
                                            value="{{$mActivity['id']}}"
                                            data-maximum="{{$mActivity['maximum']}}"
                                            {{ isset($activity) && $activity->mining_activity_id === $mActivity['id'] ? 'selected' : '' }}
                                            {{ old('mining_activity_id') === $mActivity['id'] ? 'selected' : '' }}>{{$mActivity['nameAndAbbreviation']}}</option>
                                    @endforeach
                                    
                                </select>
                                
                                @if ($errors->has('mining_activity_id'))
                                <div class="text-danger">
                                    {!! $errors->first('mining_activity_id') !!}
                                </div>
                                @endif
                                
                            </div>
                            
                            <div class="clearfix"></div>
                            
                            <div class="col-md-3 col-md-offset-3">
                                <div class="form-group">
                                    {!! Form::label('quantity', 'Cantidad') !!}
                                    {!! Form::number('quantity', null, ['class' => 'form-control', 'id' => 'quantity', 'step' => '0.01', 'min' => '0.1']) !!}
                                    @if ($errors->has('quantity'))
                                    <div class="text-danger">
                                        {!! $errors->first('quantity') !!}
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if(\Auth::getUser()->can('activityReport.assignCosts'))
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('price', 'Precio') !!}
                                    {!! Form::number('price', null, ['class' => 'form-control', 'step' => '1']) !!}
                                    
                                    @if ($errors->has('price'))
                                    <div class="text-danger">
                                        {!! $errors->first('price') !!}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div class="clearfix"></div>
                            
                            <div class="col-md-3 col-md-offset-3">
                                <div class="form-group">
                                <label for="reported_at">Fecha de Actividad</label>
                                <div class="input-group">
                                    {!! Form::text('reported_at', isset($activity->reported_at) ? $activity->reported_at->toDateString() : null, ['class' => 'form-control', 'id' => 'reported_at', 'placeholder' => 'Elegir Fecha', 'readonly']) !!}
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
                            
                            <div class="clearfix"></div>
                            
                            <div class="col-md-6 col-md-offset-3">
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
                            
                            <div class="clearfix"></div>
                            
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="{{$btn_options['class']}}">
                                    <span class="{{$btn_options['icon']}}"></span>
                                    {{$btn_options['caption']}}
                                </button>
                            </div>
                        
                        @endif

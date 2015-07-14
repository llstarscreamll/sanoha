                    {{-- Tab panes --}}
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="user">
                            
                            <fieldset>
                                
                            <div class="row">
            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('name', 'Nombre') !!}
                                        {!! Form::input('text', 'name', null, ['class' => 'form-control', 'required' => "required", isset($show) ? 'disabled' : false]) !!}
                                        @if ($errors->has('name'))
                                            <div class="text-danger">
                                                {!! $errors->first('name') !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('display_name', 'Alias') !!}
                                        {!! Form::input('text', 'display_name', null, ['class' => 'form-control', 'required' => "required", isset($show) ? 'disabled' : false]) !!}
                                        @if ($errors->has('display_name'))
                                            <div class="text-danger">
                                                {!! $errors->first('display_name') !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('description', 'Descripción') !!}
                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '4', isset($show) ? 'disabled' : false]) !!}
                                        @if ($errors->has('description'))
                                            <div class="text-danger">
                                                {!! $errors->first('description') !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                            </div>
                            
                            </fieldset>
                            
                        </div>
                        
                        <div role="tabpanel" class="tab-pane fade" id="permissions">
                            
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                
                                @if(!empty($permissions))
                                
                                @foreach($categories as $key => $value)
                              
                                    @if(array_key_exists($key, $permissions))
                                        <div class="panel panel-default">
                                            
                                            <div class="panel-heading cursor-pointer" role="tab" id="heading_{{ $key }}" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                                <h4 class="panel-title">
                                                    {{ $value }}
                                                </h4>
                                            </div>
                                            
                                            <div id="collapse{{$key}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{ $key }}">
                                                <div class="panel-body">
                                                  
                                                    @foreach($permissions[$key] as $permission)
                                                        
                                                        <div class="">
                                                            <div class="">
                                                                <div class="col-xs-4 col-sm-3 margin-top-5">
                                                                    {!! Form::label($permission['name'], $permission['display_name']) !!}
                                                                </div>
                                                                <div class="col-xs-8 col-sm-9 margin-bottom-10">
                                                                    {!! Form::checkbox(
                                                                        'permissions[]',
                                                                        $permission['name'],
                                                                        isset($show) || in_array($permission['name'], isset($rolePermissions) ? $rolePermissions : []) ? true : false,
                                                                        [
                                                                            'id' => $permission['name'], 
                                                                            'class' => 'bootstrap_switch',
                                                                            'data-size' => 'small',
                                                                            'data-on-text' => 'SI',
                                                                            'data-off-text' => 'NO',
                                                                            isset($show) ? 'disabled' : false
                                                                        ])
                                                                    !!}
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        
                                                    @endforeach
                                                    
                                                </div>
                                            </div>
                                            
                                        </div>
                                    @endif
                                    
                                @endforeach
                                
                                @else
                                
                                    <div class="alert alert-warning">
                                        No se han asignado permisos.
                                    </div>
                                
                                @endif
                              
                            </div>
                            
                        </div>
                        
                    </div>
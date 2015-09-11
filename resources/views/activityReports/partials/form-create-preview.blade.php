                    {{-- ***************************************
                        Vista previa se los cambios realizados
                    ********************************************--}}
                    <fieldset class="margin-top-20">
                        
                        <legend>
                            Vista Previa de Actividades
                            <span data-toggle="tooltip" data-placement="top" title="Fecha de Reporte" class="small-date">
                                {{ isset($activity) ? $activity->created_at->format('l j \\of F Y') : \Carbon\Carbon::now()->format('l j \\of F Y') }}
                            </span>
                        </legend>
                        
                        @if(!is_null($parameters['employee_id']))
                        
                        <div class="table-responsive">

                            <table class="table table-hover table-bordered table-vertical-align-middle">
                                
                                <thead>
                                    
                                    <tr>
                                        <th>
                                            Nombres
                                        </th>
                                        
                                        @foreach($miningActivities as $activity)
                                            <th class="text-center">
                                                <span title="{{$activity['name']}}" data-toggle="tooltip">
                                                    {{ $activity['short_name'] }}
                                                </span>
                                            </th>
                                        @endforeach
                                        <th class="text-center">
                                            <span title="Total Empleado" data-toggle="tooltip">
                                                TOTAL
                                            </span>
                                        </th>
                                    </tr>
                                    
                                </thead>
                                
                                <tbody>
                                    
                                    @if(!empty($orderedActivities))
                                        
                                        {{-- No print the reported_by data --}}
                                        @foreach($orderedActivities as $key => $activity)
                                            
                                            @if($key !== 'reported_by')
                                            <tr class="{{ $key === 'totals' ? 'text-bold' : '' }}">
                                                
                                                @foreach($activity as $k => $v)
                            
                                                    @if(!is_array($v))
                                                    <td class="{{ $k !== 'employee_fullname' ? 'text-center' : '' }}">
                                                        <span>{{$v}}</span> {{-- El nombre del empelado --}}
                                                    </td>
                                                    @else
                                                    <td class="{{ $k !== 'employee_fullname' ? 'text-center' : '' }}">
                                                        <span data-toggle="tooltip" data-placement="top" title="{{$k == 'employee_total' ? 'Total Actividades '.$v['employee'] : 'Cantidad '.$k}}">{{ $v['quantity'] }}</span><br>
                                                        <div>
                                                            <span data-toggle="tooltip" data-placement="bottom" title="{{$k == 'employee_total' ? 'Precio Total '.$v['employee'] : 'Precio '.$k}}">{{number_format($v['price'], 0, ',', '.')}}</span>
                                                        </div>
                                                    </td>
                                                    @endif
                                                    
                                                @endforeach
                                                
                                            </tr>
                                            @endif
                            
                                        @endforeach
                                        
                                        {{--
                                                @foreach($activity as $k => $v)
                                                    @if($key !== $k.'_price')
                                                    <td class="{{ $k !== 'employee_fullname' ? 'text-center' : '' }} ">
                                                        {{ $v }}
                                                    </td>
                                                    @endif
                                                @endforeach
                                                --}}
                                        
                                    
                                    @else
                                        
                                        <tr>
                                            <td colspan={{count($miningActivities)+1}}>
                                                
                                                <div class="alert alert-warning margin-bottom-0">
                                                    No hay actividades registradas...
                                                </div>
                                                
                                            </td>
                                        </tr>
                                        
                                    @endif
                                    
                                </tbody>
                                
                            </table>
                        
                        </div>
                        @else
                            
                            <div class="alert alert-warning">
                                Selecciona un trabajador...
                            </div>
                            
                        @endif
                        
                    </fieldset>
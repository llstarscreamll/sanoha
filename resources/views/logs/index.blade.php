@extends('app')

@section('title')
	Logs de Usuarios
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Logs de Usuarios
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				<div class="list-group">
                        
                    @foreach($latestActivities as $activity)
                    
                    <li class="list-group-item">
                        <a class="text-{{!isset(explode('|', $activity->text)[6]) ?: explode('|', $activity->text)[6]}}" data-toggle="collapse" href="#details-{{$activity->id}}" aria-expanded="false" aria-controls="details-{{$activity->id}}">
                            {!! str_replace('@user', $activity->user->fullname, explode('|', $activity->text)[0]) !!}, el <strong>{{$activity->created_at}}</strong>
                        </a>

                        <div class="row margin-top-15 collapse" id="details-{{$activity->id}}">

                                <div class="col-md-6">
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            
                                            <th colspan=2 class="text-center">
                                                <a href="{{route(explode('|', $activity->text)[3], explode('|', $activity->text)[4])}}">
                                                    Registro
                                                </a>
                                            </th>
                                        </tr>
                                        @foreach(json_decode(explode('|', $activity->text)[5]) as $key => $value)
                                        
                                        <tr>
                                            <th>{{$key}}</th>
                                            <td>{{$value}}</td>
                                        </tr>
                                        
                                        @endforeach
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <th colspan=2 class="text-center">
                                                <a href="{{route('users.show', $activity->user->id)}}">
                                                    Usuario
                                                </a>
                                            </th>
                                        </tr>
                                        
                                        <tr>
                                            <th>Código</th>
                                            <td>{{$activity->user->id}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Nombres</th>
                                            <td>{{$activity->user->fullname}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Email</th>
                                            <td>{{$activity->user->email}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Rol</th>
                                            <td>{{$activity->user->getRoles()}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Centro de Costo</th>
                                            <td>{{$activity->user->getSubCostCenters()}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Estado</th>
                                            <td>{!!$activity->user->activated ? '<span class="text-success">Activado</span>' : '<span class="text-danger">Desactivado</span>'!!}</td>
                                        </tr>
                                        
                                    </table>
                                    
                                </div>
                                
                                <div class="clearfix"></div>
                                
                        </div>
                        
                    </li>

    				@endforeach
                </div>
                
                {!! $latestActivities->render() !!}

			</div>
		</div>
	</div>
@endsection

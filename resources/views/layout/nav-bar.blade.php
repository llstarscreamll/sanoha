    <nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ ! Auth::guest() ? url('home') : url('/') }}">Sanoha</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					@if (! Auth::guest())
						
						{{--
							*******************************************
							Módulo de Usuarios
							*******************************************
						--}}
						@if(\Auth::getUser()->can('users.index'))
							<li class="{{ \Request::is('user*') ? 'active' : '' }}"><a href="{{ url('/users') }}">Usarios</a></li>
						@endif
						{{--
							*******************************************
							Múdulo de Roles
							*******************************************
						--}}
						@if(\Auth::getUser()->can('roles.index'))
							<li class="{{ \Request::is('role*') ? 'active' : '' }}"><a href="{{ url('/roles') }}">Roles</a></li>
						@endif
						
						{{--
							*******************************************
							Múdulo de Empleados
							*******************************************
						--}}
						@if(\Auth::getUser()->can('employee.index'))
							<li class="{{ \Request::is('employee*') ? 'active' : '' }}"><a href="{{ url('/employee') }}">Empleados</a></li>
						@endif
						
						{{--
							*******************************************
							Múdulo de Reporte de Labores Mineras
							*******************************************
						--}}
						@if(\Auth::getUser()->can('activityReport.index'))
							<li class="{{ \Request::is('activityReport*') ? 'active' : '' }} dropdown" id="activityReports">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reporte de Actividades <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									
									{{--
										*******************************************
										Los proyectos o centros de costo asignados
										*******************************************
									--}}
									
									@if(count(Auth::getUser()->getCostCenters()) > 0)
										
										@foreach(Auth::getUser()->getCostCenters() as $center)
											<li><a href="{{ url(route('activityReport.setCostCenter', [ $center['id'] ])) }}">{{ $center['name'] }}</a></li>
										@endforeach
									
									@else
										<li><a href="#">No tienes proyectos asignados</a></li>
									@endif
									
								</ul>
							</li>
						@endif
						{{--
							*******************************************
							Múdulo de Reporte de Novedades
							*******************************************
						--}}
						@if(\Auth::getUser()->can('noveltyReport.index'))
							<li class="{{ \Request::is('noveltyReport*') ? 'active' : '' }}" id="noveltyReports">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reporte de Novedades <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									
									{{--
										*******************************************
										Los proyectos o centros de costo asignados
										*******************************************
									--}}
									
									@if(count(Auth::getUser()->getCostCenters()) > 0)
										
										@foreach(Auth::getUser()->getCostCenters() as $center)
											<li><a href="{{ url(route('noveltyReport.setCostCenter', [ $center['id'] ])) }}">{{ $center['name'] }}</a></li>
										@endforeach
									
									@else
										<li><a href="#">No tienes proyectos asignados</a></li>
									@endif
									
								</ul>
							</li>
						@endif
					@endif
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Iniciar Sesión</a></li>
						<li><a href="{{ url('/auth/register') }}">Regístrate</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Salir</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
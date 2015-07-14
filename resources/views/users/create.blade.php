@extends('app')

@section('title')
	Crear Usuario
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Crear Usuario
				</h1>
			</div>

			<div class="panel-body">

				@include ('layout.notifications')

				{!! Form::open(['route' => 'users.store', 'method' => 'POST']) !!}

					@include ('users.partials.form', ['btnText' => 'Crear', 'password_required' => 'required'])
					
					<button type="submit" class="btn btn-primary">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						Crear
					</button>

				{!! Form::close() !!}

			</div>
		</div>
	</div>
@endsection

@section('script')

    <script type="text/javascript">
        
        $(".bootstrap_switch").bootstrapSwitch();
        
    </script>
    
@endsection

@extends('app')

@section('title')
    Actualizar Usuario
@stop

@section('content')

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>
                    Actualizar Usuario
                </h1>
            </div>

            <div class="panel-body">

                @include ('layout.notifications')

                {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT']) !!}

                    @include ('users.partials.form', ['btnText' => 'Actualizar', 'password_required' => ''])
                    
                    <div class="col-md-10 col-md-offset-1">
                        <button type="submit" class="btn btn-warning">
    						<span class="glyphicon glyphicon-pencil"></span>
    						Actualizar
    					</button>
    				</div>

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
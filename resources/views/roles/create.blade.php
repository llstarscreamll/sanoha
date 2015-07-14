@extends('app')

@section('title')
    Crear Rol
@stop

@section('content')


    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>
                    Crear Rol
                </h1>
            </div>
            
            <div class="panel-body">
                
            @include ('layout.notifications')
            
            {{-- Panel Tabs --}}
                
                <div id="myTab" role="tabpanel">

                    <ul class="nav nav-tabs margin-bottom-20" role="tablist">
                        <li role="presentation" class="active"><a href="#user" aria-controls="user" role="tab" data-toggle="tab">Usuario</a></li>
                        <li role="presentation"><a href="#permissions" aria-controls="permissions" role="tab" data-toggle="tab">Permisos</a></li>
                    </ul>
                    
                {!! Form::open(['method' => 'POST', 'route' => 'roles.store']) !!}
                
                    @include('roles.partials.form')
                    
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-floppy-disk"></span>
                        Crear
                    </button>
                    
                {!! Form::close() !!}
                
                </div>
                
            </div>
            
        </div>

    </div>
    
    
@endsection

@section('script')

    <script type="text/javascript">
    
        $('#my-tab a').click(function (e) {
            e.preventDefault();
        $(this).tab('show');
        });
        
        $(".bootstrap_switch").bootstrapSwitch();
        
    </script>
    
@endsection

@extends('app')

@section('title')
    Editar Rol
@stop

@section('content')

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            
            <div class="panel-heading">
                <h1>
                    Editar Rol
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
                    
                    {!! Form::model($role, ['method' => 'PUT', 'route' => ['roles.update', $role->id]]) !!}
                        
                        @include('roles.partials.form', ['edit' => true])
                        
                        <button type="submit" class="btn btn-warning">
                            <span class="glyphicon glyphicon-pencil"></span>
                            Actualizar
                        </button>
                        
                    {!! Form::close() !!}
                
                </div>
                
                {{-- Action buttons --}}
                
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

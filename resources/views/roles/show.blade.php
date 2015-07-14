@extends('app')

@section('title')
    Detalles del Rol
@stop

@section('content')

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            
            <div class="panel-heading">
                <h1>
                    Detalles del Rol
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
                    
                    {!! Form::model($role, ['method' => 'POST', 'route' => 'roles.index']) !!}
                        
                        @include('roles.partials.form', ['show' => true])
                        
                    {!! Form::close() !!}
                
                </div>
                
                {{-- Action buttons --}}
                <a href="{!! url(route('roles.edit', $role->id)) !!}" class="btn btn-warning">
                    <span class="glyphicon glyphicon-pencil"></span>
                    Editar
                </a>
                
                {{-- This button triggers the confirmation modal window --}}
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_confirm">
                        <span class="glyphicon glyphicon-trash"></span>
                        Mover a Papelera
                    </button>
            </div>
            
        </div>
    </div>
    
    {{-- Modal Window --}}
    <div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Está Seguro?</h4>
            </div>
            <div class="modal-body">
              No volvera a ver la información del rol <strong>{{ $role->display_name }}</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                {!! Form::model($role, ['route' => ['roles.destroy', $role->id], 'method' => 'DELETE', 'class' => 'inline']) !!}
                    <button type="submit" class="btn btn-danger">Confirmar</button>
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

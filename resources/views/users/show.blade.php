@extends('app')

@section('title')
    Detalles de Usuario
@stop

@section('content')

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>
                    Detalles de Usuario
                </h1>
            </div>

            <div class="panel-body">

                @include ('layout.notifications')

                <form>
                
                <fieldset>
                    <legend>Detalles</legend>
                    
                    <div class="row">
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('name', 'Nombres', '') !!}
                                {!! Form::input('text', 'name', $user->name, ['class' => 'form-control', 'disabled', 'readonly']) !!}
                            </div>
                        </div>
            
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('lastname', 'Apellidos', '') !!}
                                {!! Form::input('text', 'lastname', $user->lastname, ['class' => 'form-control', 'disabled', 'readonly']) !!}
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('email', 'Correo Electrónico', '') !!}
                                {!! Form::email('email', $user->email, ['class' => 'form-control', 'disabled', 'readonly']) !!}
                            </div>
                        </div>
                        
                    </div>
                </fieldset>
                
                <fieldset>
                    <legend>Autorización</legend>
                    
                    <div class="row">
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                {!! Form::label('role_id', 'Tipo de Usuario', '') !!}
                                {!! Form::input('text', 'role_id', $user->getRoles(), ['class' => 'form-control', 'disabled', 'readonly']) !!}
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                {!! Form::label('costCenter_id', 'Centros de Costo', '') !!}
                                {!! Form::input('text', 'costCenter_id', $user->getSubCostCenters(), ['class' => 'form-control', 'disabled', 'readonly']) !!}
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::label('activated', 'Estado', '') !!}
                                {!! Form::input('text', 'activated', $user->getActivatedState(), ['class' => 'form-control', 'disabled', 'readonly']) !!}
                            </div>
                        </div>
                        
                    </div>
                </fieldset>

                </form>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                    <span class="glyphicon glyphicon-pencil"></span>
                    Editar
                </a>
                {{-- This button triggers the confirmation modal window --}}
                <button class="btn btn-danger" data-toggle="modal" data-target="#modal_confirm">
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
              No volvera a ver la información de <strong>{{ $user->name . ' ' . $user->lastname }}</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'DELETE', 'class' => 'display-inline']) !!}
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                {!! Form::close() !!}
            </div>
          </div>
        </div>
    </div>
    
@endsection

@section('scripts')

@stop()
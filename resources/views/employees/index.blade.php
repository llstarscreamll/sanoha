@extends('app')

@section('title')
    Empleados
@stop

@section('content')
    
    <div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
		    
			<div class="panel-heading">
        		<h1>
        			<a class="link-black" href="{{route('employee.index')}}">Empleados</a>

					{{-- Action Buttons --}}
					<small class="action-buttons hidden-print">
						
						<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Activar Empleado(s)">
							<button
								type="button"
								class="btn btn-default btn-sm"
								id="btn-enable-employee"
								data-action="{{route('employee.status', 'enabled')}}"
								data-method="PUT"
								{{-- abre la ventana modal de confirmación --}}
								data-message="Desea activar a el(los) empleado(s) marcado(s)? Se verán disponibles sus datos para realizar operaciones en los respectivos módulos del sistema."
								data-toggle="modal"
								data-target="#confirm-modal"
							>
								<span class="glyphicon glyphicon-eye-open"></span>
								<span class="sr-only">Activar Empleado(s)</span>
							</button>
						</div>
						
						<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Desactivar Empleado(s)">
							<button 
								type="button"
								class="btn btn-default btn-sm"
								id="btn-disable-employee"
								data-action="{{route('employee.status', 'disabled')}}"
								data-method="PUT"
								{{-- abre la ventana modal de confirmación --}}
								data-message="Desea desactivar a el(los) empleado(s) marcado(s)? No estará disponible en los módulos del sistema, pero sus datos históricos no se verán afectados."
								data-toggle="modal"
								data-target="#confirm-modal"
							>
								<span class="glyphicon glyphicon-eye-close"></span>
								<span class="sr-only">Desactivar Empleado(s)</span>
							</button>
						</div>
						
						<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Mover Empleado(s) Papelera">
							<button
								type="button"
								class="btn btn-default btn-sm"
								id="btn-trash"
								data-action="{{route('employee.destroy')}}"
								data-method="DELETE"
								{{-- abre la ventana modal de confirmación --}}
								data-message="El(los) empleado(s) seleccionados serán movidos a la papelera, pero sus datos históricos no se verán afectados."
								data-toggle="modal"
								data-target="#confirm-modal"
							>
								<span class="glyphicon glyphicon-trash"></span>
								<span class="sr-only">Mover Empleados a Papelera</span>
							</button>
						</div>
						
						<a id="create-user-link" class="btn btn-default btn-sm" href="{!! route('employee.create') !!}" role="button"  data-toggle="tooltip" data-placement="top" title="Crear Empleado">
							<span class="glyphicon glyphicon-plus"></span>
							<span class="sr-only">Crear Empleado</span>
						</a>
						
					</small>
				</h1>
			</div>
			
            <div class="panel-body">
				
				@include ('layout.notifications')
				
				<div class="row hidden-print">
				    @include ('employees.partials.searchForm')
				</div>
				
				@include ('employees.partials.index-table')
				
            </div>
        </div>
    </div>
    
    {{-- La ventana modal de confirmación --}}
    <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-modal-label">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				
			    <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    <h4 class="modal-title" id="exampleModalLabel">Está seguro?</h4>
			    </div>
			    
			    <div class="modal-body">
				    <div class="modal-message"></div>
			    </div>
			    
			    <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" id="btn-confirm" class="btn btn-danger">Confirmar</button>
			    </div>
			    
			</div>
		</div>
	</div>
    
@endsection

@section('script')

    <script type="text/javascript">
        
        $(document).ready(function(){
        	
        	$('#btn-confirm').click(function(event){
        		var btn = $(event.target);
        		var form = $('form[name=table-form]');
        		var method = $('input[name=_method]');
        		
        		form.attr({
        			'action' : btn.attr('data-action')
        		});
        		
        		method.attr('value', btn.attr('data-method'));
        		form.submit();
        	});
        	
        	{{-- Configura el contenido de la ventana modal --}}
        	$('#confirm-modal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				var method = button.data('method');
				var action = button.data('action');
				var message = button.data('message');

				var modal = $(this)
				modal.find('.modal-body .modal-message').text(message)
				modal.find('.modal-footer button#btn-confirm').attr('data-method', method)
				modal.find('.modal-footer button#btn-confirm').attr('data-action', action)
			});
            
            {{-- searching if there are checkboxes checked to toggle enable action buttons --}}
            scanCheckedCheckboxes('.checkbox-table-item');
            
            {{-- toggle the checkbox checked state from row click --}}
            toggleCheckboxFromRowClick();
            
            {{-- toggle select all checkboxes --}}
            toggleCheckboxes();
            
            {{-- listen click on checkboxes to change row class and count the ones checked --}}
            $('.checkbox-table-item').click(function(event) {
                scanCheckedCheckboxes('.'+$(this).attr('class'));
                event.stopPropagation();
            });
            
            {{-- Initialize all tooltips --}}
            $('[data-toggle="tooltip"]').tooltip();
            
        });
        
    </script>

@stop()
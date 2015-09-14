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
						
						<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Mover Empleado(s) Papelera">
							<button type="submit" class="btn btn-default btn-sm" id="btn-trash">
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
				
				@include ('employees.partials.index-table')
				
            </div>
        </div>
    </div>
    
@endsection

@section('script')

    <script type="text/javascript">
        
        $(document).ready(function(){
        	
        	{{-- trigger form submit when click on action buttons --}}
        	$('.action-buttons button[type=submit]').click(function(){
        		$('form[name=table-form]').submit();
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
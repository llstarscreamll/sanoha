@extends('app')

@section('title')
    Roles
@stop

@section('content')
    
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>
                    <a href="{{ url(route('roles.index'))}}" class="link-black">
                        Roles
                    </a>
                    <small class="action-buttons">
                        
                        <div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Mover a Papelera">
                            <button type="submit" class="btn btn-default btn-sm" id="btn-trash" disabled="disabled">
                                <span class="glyphicon glyphicon-trash"></span>
                                <span class="sr-only">Mover Rol a la Papelera</span>
                            </button>
                        </div>
                        
                        <a class="btn btn-default btn-sm" href="{!! route('roles.create') !!}" role="button" data-toggle="tooltip" data-placement="top" title="Crear Rol">
                            <span class="glyphicon glyphicon-plus"></span>
                            <span class="sr-only">Crear Rol</span>
                        </a>
                        
                    </small>
                </h1>
            </div>

            <div class="panel-body">
                
                @include ('layout.notifications')

                @include ('roles.partials.table')

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
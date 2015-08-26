@extends('app')

@section('title')
	Reporte de Labores Mineras
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					Reporte de Labores Mineras
					<small class="action-icons">
						<a class="btn btn-default btn-sm" href="#" role="button"><span class="glyphicon glyphicon-plus"></span></a>
					</small>
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				<div class="row hidden-print">
				    @include ('activityReports.partials.views-links')
				</div>
				
				<div class="table-responsive margin-top-10">
				    <table class="table table-hover table-bordered table-vertical-align-middle">
				        
				        <thead>
				        	<tr>
				        		<th colspan=6>
				        			{{\Session::get('current_cost_center_name')}}
				        		</th>
				        	</tr>
				            <tr>
				                <th>#</th>
				                <th>Empleado</th>
				                <th>Actividad</th>
				                <th>Cantidad</th>
				                <th>Precio</th>
				                <th>Total</th>
				            </tr>
				        </thead>
				        
				        <tbody>
				            @foreach($activities as $activity)
				            	
				            	<tr>
				            		<td><a href="{{route('activityReport.show', $activity->id)}}">{{$activity->id}}</a></td>
				            		<td>{{ucwords(strtolower($activity->employee->fullname))}}</td>
				            		<td>{{$activity->miningActivity->name}}</td>
				            		<td>{{$activity->quantity}}</td>
				            		<td>{{number_format($activity->price, 0, ',', '.')}}</td>
				            		<td>{{number_format($activity->quantity * $activity->price, 0, ',', '.')}}</td>
				            	</tr>
				            	
				            @endforeach
				        </tbody>
				        
				    </table>
				</div>
				{!!$activities->render()!!}

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
    
@stop
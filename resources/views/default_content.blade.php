@extends('app')

@section('title')
	{{-- Here page title!! --}}
@stop

@section('content')

	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>
					{{-- Section title --}}
					<small class="action-icons">
						<a class="btn btn-default btn-sm" href="{!! route('admin.users.create') !!}" role="button"><span class="glyphicon glyphicon-plus"></span></a>
					</small>
				</h1>
			</div>

			<div class="panel-body">
				
				@include ('layout.notifications')
				
				{{-- Here content!! --}}

			</div>
		</div>
	</div>
@endsection

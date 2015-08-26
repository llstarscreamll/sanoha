@extends('app')

@section('title')
	Home
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				
				<div class="panel-heading">
					<h1 class="">Home</h1>
				</div>

				<div class="panel-body">
					
					Bienvenido {{ Auth::getUser()->name }} {{ Auth::getUser()->lastname }}!
					
					@include ('layout.notifications')
					
				</div>
				
			</div>
		</div>
	</div>
</div>
@endsection

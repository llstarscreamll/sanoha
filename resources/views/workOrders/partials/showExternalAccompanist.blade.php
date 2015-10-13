<div id="external-accompanists" class="margin-top-20">
	
	@if(count($accompanists = $workOrder->externalAccompanists) > 0)
	
	<div class="panel col-md-10 col-md-offset-1">
		{{-- List group --}}
		<ul class="list-group">
				@foreach($accompanists as $accompanst)
					<li class="list-group-item">{{$accompanst->fullname}}</li>
				@endforeach
		</ul>
	</div>
	
	@else
		<div class="col-md-8 col-md-offset-2 alert alert-warning">No se registraron acompañantes externos...</div>
	@endif
	
</div>
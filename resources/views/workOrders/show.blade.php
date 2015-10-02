@extends('app') @section('title') Detalles de Orden de Trabajo @stop @section('content')

<div class="col-md-10 col-md-offset-1">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1>
				Detalles de Orden de Trabajo
			</h1>
		</div>

		<div class="panel-body">

			@include ('layout.notifications')

			<div>

				{{-- Nav tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#work_order_details" aria-controls="work_order_details" role="tab" data-toggle="tab">Detalles de Orden de Trabajo</a></li>
					<li role="presentation"><a href="#Reporte" aria-controls="Reporte" role="tab" data-toggle="tab">Reporte</a></li>
					<li role="presentation"><a href="#internal_accompanist" aria-controls="internal_accompanist" role="tab" data-toggle="tab">Acompañantes Internos</a></li>
					<li role="presentation"><a href="#external_accompanist" aria-controls="external_accompanist" role="tab" data-toggle="tab">Acompañantes Externos</a></li>
				</ul>

				{{-- Tab panes --}}
				<div class="tab-content">

					{{-- Detalles de la orden de trabao --}}
					<div role="tabpanel" class="tab-pane fade in active" id="work_order_details">

						@include('workOrders.partials.showDetails')

					</div>

					{{-- Reporte de la orden de trabao --}}
					<div role="tabpanel" class="tab-pane fade in" id="Reporte">

						@include('workOrders.partials.showReport')

					</div>

					{{-- Acompañantes internos de la orden de trabao --}}
					<div role="tabpanel" class="tab-pane fade in" id="internal_accompanist">

						@include('workOrders.partials.showInternalAccompanists')

					</div>

					{{-- Acompañantes externos de la orden de trabao --}}
					<div role="tabpanel" class="tab-pane fade in" id="external_accompanist">

						@include('workOrders.partials.showExternalAccompanist')

					</div>

				</div>

			</div>
			{{-- Nav Tabs End --}}

		</div>
	</div>
</div>
@endsection

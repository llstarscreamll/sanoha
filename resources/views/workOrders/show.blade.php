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

			{{-- Nav Tabs End --}}

		</div>
	</div>
</div>

{{--
	Ventana modal de confirmación de acciones, el contenido de la ventana varia
	según los datos que se le envíen desde el botón que la lanza
--}}

<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="confirm-modal-label">Está seguro?</h4>
      </div>
      <div class="modal-body">
        
        <div class="modal-message">
        	{{-- Aquí va el mensaje de la ventana --}}
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        {!!Form::open(['route' => 'workOrder.show', 'method' => 'DELETE', 'class' => 'modal-form display-inline-block'])!!}
        	<button type="submit" class="btn btn-danger">Confirmar</button>
        {!!Form::close()!!}
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')

    <script type="text/javascript">
        
        $(document).ready(function(){
        	
        	{{-- Configura contenido de la ventana modal según los atributos del botón que lanza la ventana --}}
        	$('#confirm-modal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				var method = button.data('method');
				var action = button.data('action');
				var message = button.data('message');

				var modal = $(this)
				modal.find('.modal-body .modal-message').text(message)
				modal.find('.modal-footer form.modal-form input[name=_method]').attr('value', method)
				modal.find('.modal-footer form.modal-form').attr('action', action)
			});

            {{-- Initialize all tooltips --}}
            $('[data-toggle="tooltip"]').tooltip();
            
        });
        
    </script>

@stop()
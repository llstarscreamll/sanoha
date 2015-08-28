<div class="col-md-6 margin-bottom-10">
    
    {{-- Link a la vista calendario --}}
    <a class="btn btn-default" href="{{route('noveltyReport.calendar')}}" role="button"  data-toggle="tooltip" data-placement="top" title="Reporte en Calendario">
        <span class="glyphicon glyphicon-calendar"></span>
        <span class="sr-only">Reporte en Calendario</span>
    </a>
    
    {{-- Link a la vista de reportes individuales --}}
    <a class="btn btn-default" href="{{route('noveltyReport.index')}}" role="button"  data-toggle="tooltip" data-placement="top" title="Reporte de Registros Individuales">
        <span class="glyphicon glyphicon-th-list"></span>
        <span class="sr-only">Reporte de Registros Individuales</span>
    </a>
    
</div>
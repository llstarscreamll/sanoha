<div class="col-md-6 margin-bottom-10">
    
    {{-- Link a la vista calendario --}}
    <a class="btn btn-default" href="{{route('activityReport.calendar')}}" role="button"  data-toggle="tooltip" data-placement="top" title="Reporte en Calendario">
        <span class="glyphicon glyphicon-calendar"></span>
        <span class="sr-only">Reporte en Calendario</span>
    </a>
    
    {{-- Link a la vista de tabla --}}
    <a class="btn btn-default" href="{{route('activityReport.index')}}" role="button"  data-toggle="tooltip" data-placement="top" title="Reporte de Nómina">
        <span class="glyphicon glyphicon-usd"></span>
        <span class="sr-only">Reporte de Nómina</span>
    </a>
    
    {{-- Link a la vista de reportes individuales --}}
    <a class="btn btn-default" href="{{route('activityReport.individual')}}" role="button"  data-toggle="tooltip" data-placement="top" title="Reporte de Registros Individuales">
        <span class="glyphicon glyphicon-th-list"></span>
        <span class="sr-only">Reporte de Registros Individuales</span>
    </a>
    
</div>
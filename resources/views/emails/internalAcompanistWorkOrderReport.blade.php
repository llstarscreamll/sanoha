<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Reporte de Actividades de Orden de Trabajo | Sanoha Web System</title>

	{{--<link href="{{ asset('/css/app.css') }}" rel="stylesheet">--}}
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link href="{{ asset('/css/style.css') }}" rel="stylesheet">
	
	<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>	
	<div class="container-fluid">
	<div class="row">
		
		<div id="work-order-report" class="margin-top-20">
		    	
        	<div class="panel panel-default">
        		<div class="panel-heading" role="tab" id="headingOne">
        			<h2 class="panel-heading">Reporte de {{$data['employee']}}</h2>
        		</div>
        		
				<div class="panel-body">
					{!!$data['work_report']!!}
				</div>
				
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-6">
						</div>
						<div class="col-md-6 text-right">
							Reportado por <strong>{{$data['reported_by']}}</strong> el <strong>{{$data['reported_at']}}</strong>
						</div>
					</div>
				</div>
				
			</div>
		</div>

	</div>
	</div>
</body>
</html>

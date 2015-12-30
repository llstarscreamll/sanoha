<?php namespace sanoha\Http\Controllers;

use sanoha\Models\Siigo;
use sanoha\Http\Requests;
use sanoha\Jobs\ImportSiigoFile;
use sanoha\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SiigoController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$siigoRows = Siigo::count();
		return view('siigo.uploadFile', compact('siigoRows'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		ini_set('max_execution_time', 10000);
		// limpio la base de datos
		\DB::table('siigo')->truncate();

		$file = $request->file('file')->move(storage_path('/app/imports/'), $name = date('Y-m-d_H-i-s').'_siigo_file.csv');
		$file_path = storage_path('imports/').$name;

        $this->dispatch(new ImportSiigoFile($file_path));

        return redirect()->route('siigo.create')->with('success', 'Tu archivo está siendo procesado, te notificaremos cuando la tarea termine.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id, Request $request)
	{
		// si de dieron los parámetros de fecha, procedo a generar el reporte
		if(! empty($request->get('from')) && ! empty($request->get('to'))){

			return \Excel::create('SanohaData', function ($excel) use($request) {
	            $excel->sheet('Data', function ($sheet) use($request) {
	            	$siigo = new Siigo;
	                $data = $siigo->getDataToExportExcel($request);
	                $sheet->fromArray($data);

	                $sheet->setStyle(array(
	                    'font' => array(
	                        'name' => 'Calibri',
	                        'size' => 8,
	                        'bold' => false,
	                    ),
	                ));
	            });
	        })->export('xlsx');
			
		}

		// si no, muestro el formulario para dar los parámetros
		$nits = Siigo::distinct()->lists('nit', 'tercero')->toArray();

		return view('siigo.reportOptions', compact('nits'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
